<!-- eslint-disable camelcase -->

<script setup>
definePage({
  meta: {
    action: 'read' || 'historical',
    subject: 'deadline-postponed',
  },
})
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { paginationMeta } from '@api-utils/paginationMeta'
import AppAutocomplete from '@/@core/components/app-form-elements/AppAutocomplete.vue';
import JsFileDownloader from 'js-file-downloader'
import { $api } from '@/utils/api';

const router = useRouter()

const type_of_credit_id = ref()
const status = ref()
const searchQuery = ref('')
const loadings = ref([])
const itemsPerPage = ref(8)
const page = ref(1)
const selectedItemId = ref(0)
const isActionDialogVisible = ref(false)
const actionTitle = ref("")
const actionText = ref("")
const actionButtonText = ref("")
const actionFunction = ref()
const actionComment = ref("")
const commentPresence = ref(false)
const actionStatus = ref("waiting")
const headers = [
  {
    title: 'Client',
    key: 'beneficiary_label',
  },
  {
    title: 'Montant',
    key: 'loan_amount',
  },
  {
    title: 'Numéro Crédit',
    key: 'credit_number',
  },
  {
    title: 'Rallonge',
    key: 'extension',
  },
  {
    title: 'Echéance',
    key: 'deadline_number',
  },
  {
    title: 'Ancienne date',
    key: 'old_date',
  },
  {
    title: 'Nouvelle date',
    key: 'new_date',
  },
  {
    title: 'Statut',
    key: 'status',
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]
const {
  data: deadlinePostponedData,
  execute: fetchPv,
} = await useApi(createUrl('/deadline-postponed', {
  query: {
    search: searchQuery,
    // type_of_credit_id: type_of_credit_id,
    // status: status,
    page: page,
    // has_contract: 0,
    // has_notification: 0,
    // has_mortgage: 0,
    with_caf: 1,
    // with_type_of_credit: 1,
  },
}))


const load = i => {
  loadings.value[i] = true
  setTimeout(() => {
    loadings.value[i] = false
  }, 1000)
}

const updateOptions = options => {
  page.value = options.page
}

const downloadFile = async (url, fileName) => {
  const userToken = useCookie('userToken').value

  try {
    new JsFileDownloader({
      url: url,
      headers: [
        { name: 'Authorization', value: `Bearer ${userToken}` },
        { name: 'Accept', value: `application/json` },
      ],
      nameCallback: function (name) {
        return fileName
      },
    })
  } catch (error) {
    console.error('Erreur lors du téléchargement:', error)
  }
}

const apiDelete = async id => {
  await $api(`verbal-trial/${id}`, { method: 'DELETE' })
  actionComment.value = ""
  fetchPv()
}

const apiChangeStatus = async id => {
  await $api(`verbal-trial/change-status/${id}`, { method: 'PUT', body: { status: actionStatus.value, comment: actionComment.value } })
  actionComment.value = ""
  if (actionStatus.value == "validated") {
    router.push(`/contract/add?id=${id}`)
  }
  fetchPv()
}
console.log(deadlinePostponedData.value.data)
const deadlinePostponedList = computed(() => deadlinePostponedData.value.data)
console.log(deadlinePostponedList)
const totalDeadlinePostponed = computed(() => deadlinePostponedData.value.total)
const lastPage = computed(() => deadlinePostponedData.value.last_page)
// Math.min(Math.ceil(totalPv / itemsPerPage), 5)
</script>

<template>
  <div>
    <!-- 👉 widgets -->
    <VCard class="mb-6">
      <VCardText>
        <VRow>
          <VCardText>
            <h2>
              Liste des Report d'échéance en attente
            </h2>
          </VCardText>
        </VRow>
      </VCardText>
    </VCard>

    <!-- 👉 pvs -->
    <VCard title="Filtres" class="mb-6">
      <VCardText>
        <VRow>
          <VCol cols="12" sm="4">
            <VSelect v-model="status" placeholder="Statut"
              :items="[{ value: 'v', title: 'Validé' }, { value: 'w', title: 'En attente' }, { value: 'r', title: 'Rejeté' }]"
              clear-icon="tabler-x" clearable="" />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider class="my-4" />

      <div class="d-flex flex-wrap gap-4 mx-5">
        <div class="d-flex align-center">
          <!-- 👉 Search  -->
          <AppTextField v-model="searchQuery" placeholder="Rechercher un pv" density="compact"
            style="inline-size: 200px;" class="me-3" />
        </div>

        <VSpacer />
        <div class="d-flex gap-4 flex-wrap align-center">
          <!-- 👉 Export button -->
          <VBtn variant="tonal" color="secondary" prepend-icon="tabler-upload">
            Export
          </VBtn>

          <VBtn v-if="$can('create', 'deadline-postponed')" color="primary" prepend-icon="tabler-plus"
            :to="{ name: 'deadline-postponed-add' }">
            Ajouter
          </VBtn>
          <VBtn :loading="loadings[3]" :disabled="loadings[3]" prepend-icon="tabler-refresh"
            @click="fetchPv(); load(3)">
            Recharger
            <template #loader>
              <span class="custom-loader">
                <VIcon icon="tabler-refresh" />
              </span>
            </template>
          </VBtn>
        </div>
      </div>

      <VDivider class="mt-4" />


      <!-- 👉 Datatable  -->
      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :headers="headers"
        :items="deadlinePostponedList" :items-length="totalPv" class="text-no-wrap" @update:options="updateOptions">
        <!-- Actions -->

        <template #item.status="{ item }">
          <VChip label :color="item.status_fr.color">
            <VTooltip v-if="item.comment" activator="parent" transition="scroll-x-transition" location="start">Raison:
              {{ item.comment }}</VTooltip>
            {{ item.status_fr.value }}
          </VChip>
        </template>

        <template #item.actions="{ item }">
          <div>
            <IconBtn v-if="$can('read', 'deadline-postponed') || $can('historical', 'deadline-postponed')"
              :to="{ name: 'deadline-postponed-id', params: { id: item.id } }">
              <VTooltip activator="parent" transition="scroll-x-transition" location="start">Details</VTooltip>
              <VIcon icon=" tabler-eye" />
            </IconBtn>
            <VBtn icon variant="text" size="small" color="medium-emphasis">
              <VIcon size="24" icon="tabler-dots-vertical" />
              <VMenu activator="parent">
                <VList>
                  <div v-if="$can('download', 'deadline-postponed')">
                    <!-- Télécharger document report d'éc -->
                    <VListItem
                      @click="downloadFile(item.request_path, `${item.id}-Demande-${item.beneficiary_label.split('/').slice(-1)[0]}}`)">
                      <template #prepend>
                        <VIcon icon="tabler-download" />
                      </template>
                      <VListItemTitle>Télécharger Demande</VListItemTitle>
                    </VListItem>
                    <!-- Télécharger contrat signé -->
                    <VListItem v-if="item.signed_contract_path"
                    @click="downloadFile(item.memo_path, `${item.id}-Memo-${item.beneficiary_label.split('/').slice(-1)[0]}}`)">

                      <template #prepend>
                        <VIcon icon="tabler-download" />
                      </template>
                      <VListItemTitle>Télécharger Mémo</VListItemTitle>
                    </VListItem>
                  </div>
                </VList>
              </VMenu>
            </VBtn>
          </div>

          <div v-if="$can('update', 'deadline-postponed') || $can('delete', 'deadline-postponed')">
            <VDivider />
            <IconBtn v-if="$can('update', 'deadline-postponed')"
              :to="{ name: 'deadline-postponed-edit-id', params: { id: item.id } }"
              :disabled="item.status == 'validated'">
              <VTooltip activator="parent" transition="scroll-x-transition" location="start">Modifier</VTooltip>
              <VIcon icon="tabler-edit" />
            </IconBtn>

            <IconBtn v-if="$can('delete', 'deadline-postponed')" :disabled="item.status == 'validated'" @click=" selectedItemId = item.id; actionTitle = 'Supprimer le PV',
              actionText = 'Voulez vous vraiment supprimer ce pv?', actionFunction = apiDelete;
            actionButtonText = 'Supprimer'; commentPresence = false; isActionDialogVisible = true;">
              <VTooltip activator="parent" transition="scroll-x-transition" location="end">Supprimer</VTooltip>
              <VIcon icon="tabler-trash" color='error' />
            </IconBtn>
          </div>

          <div
            v-if="$can('reject', 'deadline-postponed') || $can('validate', 'deadline-postponed') || $can('create', 'deadline-postponed')">
            <VDivider />
            <IconBtn v-if="$can('reject', 'deadline-postponed') && item.status != 'rejected'"
              @click="selectedItemId = item.id; actionTitle = 'Rejeter le PV', actionText = 'Voulez vous vraiment rejeter ce PV?', actionFunction = apiChangeStatus; actionButtonText = 'Rejeter'; commentPresence = true; actionStatus = 'rejected'; isActionDialogVisible = true;">
              <VTooltip activator="parent" transition="scroll-x-transition" location="start">Rejeter</VTooltip>
              <VIcon icon="tabler-x" color="error" />
            </IconBtn>
            <span v-if="item.status == 'waiting'">
              <IconBtn v-if="$can('validate', 'deadline-postponed')"
                @click="selectedItemId = item.id; actionTitle = 'Valider le PV', actionText = 'Voulez vous vraiment valider ce PV?', actionFunction = apiChangeStatus; actionButtonText = 'Valider'; commentPresence = false; actionStatus = 'validated'; isActionDialogVisible = true;">
                <VTooltip activator="parent" transition="scroll-x-transition" location="end">Valider</VTooltip>
                <VIcon icon="tabler-check" color="success" />
              </IconBtn>
            </span>
            <span v-if="item.status == 'validated'">
              <IconBtn v-if="$can('create', 'deadline-postponed')"
                :to="{ name: 'contract-add', query: { id: item.id } }">
                <VTooltip activator="parent" transition="scroll-x-transition" location="end">Créer le contrat</VTooltip>
                <VIcon icon="tabler-file-plus" color="success" />
              </IconBtn>
            </span>
          </div>
        </template>

        <template #bottom>
          <VDivider />

          <div class="d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3">
            <p class="text-sm text-medium-emphasis mb-0">
              {{ paginationMeta({ page, itemsPerPage }, totalPv) }}
            </p>

            <VPagination v-model="page" :length="lastPage"
              :total-visible="$vuetify.display.xs ? 1 : Math.min(lastPage, 5)">
              <template #prev="slotProps">
                <VBtn variant="tonal" color="default" v-bind="slotProps" :icon="false">
                  <VIcon start icon="tabler-arrow-left" />
                  Précedent
                </VBtn>
              </template>

              <template #next="slotProps">
                <VBtn variant="tonal" color="default" v-bind="slotProps" :icon="false">
                  Suivant
                  <VIcon end icon="tabler-arrow-right" />
                </VBtn>
              </template>
            </VPagination>
          </div>
        </template>
      </VDataTableServer>
    </VCard>


    <VDialog v-model="isActionDialogVisible" class="v-dialog-sm">
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="isActionDialogVisible = !isActionDialogVisible" />

      <!-- Dialog De suppression -->
      <VCard :title="actionTitle">
        <VCardText>
          {{ actionText }}

          <AppTextarea v-if="commentPresence" class="mt-3" v-model="actionComment" label="Commentaire"
            placeholder="Ex: RAS" />
        </VCardText>

        <VCardText class="d-flex justify-end gap-3 flex-wrap">
          <VBtn color="secondary" variant="tonal" @click="isActionDialogVisible = false">
            Annuler
          </VBtn>
          <VBtn @click="actionFunction(selectedItemId); isActionDialogVisible = false">
            {{ actionButtonText }}
          </VBtn>
        </VCardText>
      </VCard>
    </VDialog>


  </div>
</template>

<style lang="scss" scoped>
.custom-loader {
  display: flex;
  animation: loader 1s infinite;
}

@keyframes loader {
  from {
    transform: rotate(0);
  }

  to {
    transform: rotate(360deg);
  }
}
</style>
