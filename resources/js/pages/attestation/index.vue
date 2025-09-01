<script setup>
import axios from 'axios'
import JsFileDownloader from 'js-file-downloader'
import { useToast } from 'vue-toast-notification'
import { VDataTableServer } from 'vuetify/lib/labs/components.mjs'

definePage({
  meta: {
    action: "read",
    subject: "attestation",
  },
})

const { data: attestationsData, execute: fetchAttestations } = await useApi(
  createUrl(`/attestation/`),
)

const attestations = computed(() => {
  return attestationsData.value
})

const $toast = useToast()

const userToken = useCookie("userToken")

// État de recherche
const searchQuery = ref('')

// Variables de pagination
const currentPage = ref(1)
const itemsPerPage = ref(10)
const totalItems = ref(0)

// Listes pour les selects (édition)
const types = [
  'personne physique',
  'personne morale',
]
const civilites = [
  'Monsieur',
  'Madame',
]
const type_attestation = [
  'main levée de gage',
  'cloture',
  'endettement',
  'non endettement',
]

// Attestations filtrées basées sur la recherche
const filteredAttestations = computed(() => {
  if (!searchQuery.value) {
    return attestations.value || []
  }

  const query = searchQuery.value.toLowerCase()

  return (attestations.value || []).filter(attestation => {
    return (
      (attestation.last_name && attestation.last_name.toLowerCase().includes(query)) ||
      (attestation.first_name && attestation.first_name.toLowerCase().includes(query)) ||
      (attestation.raison_sociale && attestation.raison_sociale.toLowerCase().includes(query)) ||
      (attestation.email && attestation.email.toLowerCase().includes(query)) ||
      (attestation.phone && attestation.phone.toLowerCase().includes(query)) ||
      (attestation.city && attestation.city.toLowerCase().includes(query))
    )
  })
})

// Attestations paginées
const paginatedAttestations = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage.value
  const endIndex = startIndex + itemsPerPage.value
  return filteredAttestations.value.slice(startIndex, endIndex)
})

// Nombre total de pages
const totalPages = computed(() => {
  return Math.ceil(filteredAttestations.value.length / itemsPerPage.value)
})

// Mettre à jour le nombre total d'éléments
watch(filteredAttestations, newValue => {
  totalItems.value = newValue.length

  // Réinitialiser à la première page si on change de recherche
  if (currentPage.value > totalPages.value && totalPages.value > 0) {
    currentPage.value = 1
  }
})

// Fonctions de pagination
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const goToFirstPage = () => {
  currentPage.value = 1
}

const goToLastPage = () => {
  currentPage.value = totalPages.value
}

const goToPreviousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const goToNextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

// Modal state
const isEditModalOpen = ref(false)
const editingAttestation = ref(null)

// Form data for editing
const editForm = ref({
  // last_name: '',
  first_name: '',
  raison_sociale: '',
  // email: '',
  phone: '',
  account_number: '',
  date_de_creation_compte: '',
  type: '',
  type_attestation: '',
  civilite: '',
  montant_endettement: '',
  city: '',
})

// Open edit modal
function openEditModal(attestation) {
  editingAttestation.value = attestation
  editForm.value = {
    // last_name: attestation.last_name,
    first_name: attestation.first_name,
    civilite: attestation.civilite,
    // email: attestation.email,
    phone: attestation.phone,
    raison_sociale: attestation.raison_sociale,
    account_number: attestation.account_number,
    date_de_creation_compte: attestation.date_de_creation_compte,
    type: attestation.type,
    type_attestation: attestation.type_attestation,
    montant_endettement: attestation.montant_endettement,
    city: attestation.city,
  }
  isEditModalOpen.value = true
}

const editAttestation = async () => {
  axios
    .put(
      `/api/attestation/${editingAttestation.value.id}`,
      {
        //  last_name: editForm.value.last_name,
        first_name: editForm.value.first_name,
        raison_sociale: editForm.value.raison_sociale,
        account_number: editForm.value.account_number,
        date_de_creation_compte: editForm.value.date_de_creation_compte,
        type: editForm.value.type,
        type_attestation: editForm.value.type_attestation,
        // email: editForm.value.email,
        phone: editForm.value.phone,
        city: editForm.value.city,
      },
      {
        headers: {
          Authorization: `Bearer ${userToken.value}`,
        },
      },
    )
    .then(res => {
      if (res.status == 200) {
        let instance = $toast.success("Attestation modifiée!!", {
          position: "top-right",
        })
        isEditModalOpen.value = false
        fetchAttestations()
      }
    })
}

// Close modal
function closeEditModal() {
  isEditModalOpen.value = false
  editingAttestation.value = null
}

const downloadFile = async (url, fileName) => {
  const userToken = useCookie("userToken").value

  try {
    new JsFileDownloader({
      url: url,
      headers: [
        { name: "Authorization", value: `Bearer ${userToken}` },
        { name: "Accept", value: `application/json` },
      ],
      nameCallback: function (name) {
        return fileName
      },
    })
  } catch (error) {
    console.error("Erreur lors du téléchargement:", error)
  }
}

async function deleteAttestation(id) {
  axios
    .delete(`/api/attestation/${id}`, {
      headers: {
        Authorization: `Bearer ${userToken.value}`,
      },
    })
    .then(res => {
      if (res.status == 200) {
        let instance = $toast.success("Attestation supprimée!!", {
          position: "top-right",
        })
        fetchAttestations()
      }
    })
}

// Confirmation suppression
const isDeleteConfirmOpen = ref(false)
const attestationIdToDelete = ref(null)

function askDeleteAttestation(id) {
  attestationIdToDelete.value = id
  isDeleteConfirmOpen.value = true
}

function onDeleteConfirm(confirmed) {
  if (confirmed && attestationIdToDelete.value) {
    deleteAttestation(attestationIdToDelete.value)
  }
  attestationIdToDelete.value = null
}

function getVisiblePages() {
  const pages = []
  const maxVisiblePages = 5
  const halfVisible = Math.floor(maxVisiblePages / 2)
  
  let startPage = Math.max(1, currentPage.value - halfVisible)
  let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1)
  
  // Ajuster si on est proche de la fin
  if (endPage - startPage < maxVisiblePages - 1) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1)
  }
  
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i)
  }
  
  return pages
}
</script>

<template>
  <VCard>
    <VCardText>
      <VRow>
        <VCardText>
          <h2>Liste des attestations</h2>
        </VCardText>
        <div class="ms-2 d-flex gap-4 flex-wrap align-center items-end">
          <!-- 👉 Export button -->

          <VBtn
            v-if="$can('create', 'attestation')"
            color="primary"
            prepend-icon="tabler-plus"
            :to="{
              name: 'attestation-add',
            }"
          >
            Ajouter
          </VBtn>
        </div>
      </VRow>
    </VCardText>
  </VCard>

  <!-- Barre de recherche -->
  <VCard class="mb-4 mt-2">
    <VCardText>
      <VRow>
        <VCol cols="12" md="6">
          <VTextField
            v-model="searchQuery"
            label="Rechercher une attestation..."
            prepend-inner-icon="tabler-search"
            variant="outlined"
            clearable
            placeholder="Nom, prénom, email, téléphone, ville..."
          />
        </VCol>
        <VCol cols="12" md="6" class="d-flex align-center">
          <VBadge
            :content="filteredAttestations.length"
            :model-value="true"
            color="primary"
            class="ms-auto"
          >
            <span class="text-body-2">
              {{ filteredAttestations.length }} attestation(s) trouvée(s)
            </span>
          </VBadge>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>

  <div class="" v-if="filteredAttestations.length > 0">
    <VCard class="mb-6 mt-2">
      <VDivider class="mt-4" />
      <table class="table table-bordered w-100">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <!-- <th>Email</th> -->
            <th>Type d'attestation</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="attestation in paginatedAttestations" :key="attestation.id" class="text-center">
            <td>{{ attestation.last_name }} {{ attestation.raison_sociale }}</td>
            <td>{{ attestation.first_name }}</td>
            <!-- <td>{{ attestation.email }}</td> -->
            <td>{{ attestation.type_attestation }}</td>
            <td>
              <VBtn
                icon
                size="small"
                color="primary"
                @click="openEditModal(attestation)"
                class="me-2"
                v-if="$can('update', 'attestation')"
              >
                <VIcon>tabler-edit</VIcon>
              </VBtn>
              <VBtn
                icon
                size="small"
                color="error"
                class="me-2"
                @click="askDeleteAttestation(attestation.id)"
                v-if="$can('delete', 'attestation')"
              >
                <VIcon>tabler-trash</VIcon>
              </VBtn>
              <VBtn
                icon
                size="small"
                class="mR-2"
                color="primary"
                @click="downloadFile(
                   `/api/attestation/download/${attestation.id}`,
                    `Attestation-${attestation.id}.docx`
                )"
              >
                <VIcon>tabler-download</VIcon>
              </VBtn>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <VDivider class="mt-4" />
      <VCardActions class="d-flex justify-space-between align-center pa-4">
        <div class="d-flex align-center">
          <span class="text-body-2 me-4">
            Affichage de {{ (currentPage - 1) * itemsPerPage + 1 }} à 
            {{ Math.min(currentPage * itemsPerPage, filteredAttestations.length) }} 
            sur {{ filteredAttestations.length }} attestations
          </span>
          
          <VSelect
            v-model="itemsPerPage"
            :items="[5, 10, 20, 50]"
            label="Par page"
            variant="outlined"
            density="compact"
            class="me-4"
            style="max-width: 120px"
          />
        </div>

        <div class="d-flex align-center">
          <VBtn
            icon
            size="small"
            variant="text"
            :disabled="currentPage === 1"
            @click="goToFirstPage"
            class="me-2"
          >
            <VIcon>tabler-chevrons-left</VIcon>
          </VBtn>
          
          <VBtn
            icon
            size="small"
            variant="text"
            :disabled="currentPage === 1"
            @click="goToPreviousPage"
            class="me-2"
          >
            <VIcon>tabler-chevron-left</VIcon>
          </VBtn>

          <div class="d-flex align-center mx-2">
            <VBtn
              v-for="page in getVisiblePages()"
              :key="page"
              :color="page === currentPage ? 'primary' : 'default'"
              :variant="page === currentPage ? 'elevated' : 'text'"
              size="small"
              class="mx-1"
              @click="goToPage(page)"
            >
              {{ page }}
            </VBtn>
          </div>

          <VBtn
            icon
            size="small"
            variant="text"
            :disabled="currentPage === totalPages"
            @click="goToNextPage"
            class="me-2"
          >
            <VIcon>tabler-chevron-right</VIcon>
          </VBtn>
          
          <VBtn
            icon
            size="small"
            variant="text"
            :disabled="currentPage === totalPages"
            @click="goToLastPage"
            class="me-2"
          >
            <VIcon>tabler-chevrons-right</VIcon>
          </VBtn>
        </div>
      </VCardActions>
    </VCard>
  </div>
  <VCard v-else class="mt-2">
    <VCardText>
      <div class="text-center py-8">
        <VIcon size="64" color="grey" class="mb-4">tabler-search-off</VIcon>
        <h3 class="text-h6 mb-2">
          {{ searchQuery ? 'Aucune attestation trouvée' : 'Aucune attestation disponible' }}
        </h3>
        <p class="text-body-2 text-medium-emphasis">
          {{ searchQuery ? 'Essayez de modifier vos critères de recherche' : 'Commencez par ajouter une attestation' }}
        </p>
      </div>
    </VCardText>
  </VCard>

  <!-- Edit Modal -->
  <VDialog v-model="isEditModalOpen" max-width="600px">
    <VCard>
      <VCardTitle class="text-h5">
        Modifier l'attestation
      </VCardTitle>
      
      <VCardText>
        <VForm @submit.prevent="editAttestation">
            <VSelect class="z-index-1000 mb-2" label="Type" v-model="editForm.type" :items="types" disabled />
            <VSelect class="z-index-40 mb-2" label="Type d'attestation" v-model="editForm.type_attestation" :items="type_attestation" />
            <VSelect class="z-index-40 mb-2" v-if="editForm.type == 'personne physique'" label="Civilité" v-model="editForm.civilite" :items="civilites" />
            <div class="d-flex  gap-2 mb-2" v-if="editForm.type == 'personne physique'">
              <VTextField label="Nom" v-model="editForm.last_name" />
              <VTextField label="Prénom" v-model="editForm.first_name" />
            </div>
            <div v-if="editForm.type == 'personne morale'" class="mb-2">
              <VTextField label="Raison sociale" v-model="editForm.raison_sociale" />
            </div>
            <div class="d-flex gap-2 mb-2">
              <VTextField label="Email" v-model="editForm.email" />
              <VTextField label="Téléphone" v-model="editForm.phone" />
            </div>
            <VTextField label="Numéro de compte" class="mb-2" v-model="editForm.account_number" />
            <VTextField label="Montant d'endettement" v-if="editForm.type_attestation == 'endettement'" class="mb-2" v-model="editForm.montant_endettement" />
            <VTextField type="date" class="mb-2"  label="Date de création du compte" v-model="editForm.date_de_creation_compte" />
           <div class="d-flex gap-2">
            <VBtn type="submit" class="z-index-1000 mb-2">Modifier</VBtn>
            <VBtn type="reset" class="z-index-1000 mb-2">Réinitialiser</VBtn>
           </div>
          </VForm>
      </VCardText>
      
      <VCardActions>
        <VSpacer />
        <VBtn
          color="grey-darken-1"
          variant="text"
          @click="closeEditModal"
        >
          Annuler
        </VBtn>
        <VBtn
          color="primary"
          @click="editAttestation"
        >
          Sauvegarder
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>

  <!-- Confirm Delete Dialog -->
  <ConfirmDialog
    v-model:isDialogVisible="isDeleteConfirmOpen"
    confirmation-question="Êtes-vous sûr de vouloir supprimer cette attestation ?"
    confirm-title="Supprimé !"
    confirm-msg="Attestation supprimée avec succès."
    cancel-title="Annulé"
    cancel-msg="Suppression annulée."
    @confirm="onDeleteConfirm"
  />
</template>

