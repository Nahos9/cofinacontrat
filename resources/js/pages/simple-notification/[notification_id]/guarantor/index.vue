<!-- eslint-disable camelcase -->
<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'guarantor',
  },
})
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { paginationMeta } from '@api-utils/paginationMeta'
import JsFileDownloader from 'js-file-downloader'


const route = useRoute("notification-notification_id-guarantor")
const isDialogVisible = ref(false)
const guarantorIdToDelete = ref(0)
const searchQuery = ref('')
const refInputEl = ref()
const uploadState = ref('signed_notification')

const headers = [
  {
    title: 'Nom',
    key: 'full_name',
  },
  {
    title: 'Fonction',
    key: 'function',
  },
  {
    title: 'Pièce d\identité',
    key: 'number_of_identity_document',
  },
  {
    title: 'Numéro de téléphone',
    key: 'phone_number',
  },
  {
    title: 'Observations',
    key: 'observations',
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]

const loadings = ref([])

const load = i => {
  loadings.value[i] = true
  setTimeout(() => {
    loadings.value[i] = false
  }, 1000)
}

const itemsPerPage = ref(8)
const page = ref(1)

const updateOptions = options => {
  page.value = options.page
}

const {
  data: guarantorData,
  execute: fetchGuarantors,
} = await useApi(createUrl('/guarantor', {
  query: {
    search: searchQuery,
    with_verbal_trial: 1,
    notification_id: route.params.notification_id,
    page: page,
  },
}))

const guarantorList = computed(() => guarantorData.value.data)
const totalGuarantor = computed(() => guarantorData.value.total)
const lastPage = computed(() => guarantorData.value.last_page)

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

const uploadFile = async (id, event) => {
  const { files } = event.target;
  if (files && files.length === 1) {
    const reader = new FileReader();
    reader.onload = async () => {
      const base64Image = reader.result;
      try {
        const response = await fetch(`/api/guarantor/upload/${id}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${useCookie('userToken').value}`,
          },
          body: JSON.stringify({
            [uploadState.value]: base64Image,
          }),
        });

        if (response.ok) {
          fetchGuarantors();
        } else {
          console.error('Échec de l\'envoi du document.');
        }
      } catch (error) {
        console.error('Erreur lors de l\'envoi du document:', error);
      }
    };
    reader.readAsDataURL(files[0]);
  } else {
    console.error('Veuillez sélectionner un seul fichier.');
  }
}

const apiDelete = async id => {
  await $api(`guarantor/${id}`, { method: 'DELETE' })
  fetchGuarantors()
}
</script>

<template>
  <div>
    <VCard title="Liste des cautions" class="mb-6">
      <div class="d-flex flex-wrap gap-4 mx-5">
        <div class="d-flex align-center">
          <VRow>
            <VCol>
              <VBtn prepend-icon="tabler-arrow-left" :to="'../'">
                Notifications
              </VBtn>
            </VCol>
            <VCol>
              <AppTextField v-model="searchQuery" placeholder="Rechercher" density="compact" style="inline-size: 200px;"
                class="me-3" />
            </VCol>
          </VRow>
        </div>

        <VSpacer />
        <div class="d-flex gap-4 flex-wrap align-center">
          <!-- 👉 Export button -->
          <VBtn variant="tonal" color="secondary" prepend-icon="tabler-upload">
            Export
          </VBtn>

          <VBtn v-if="$can('create', 'guarantor')" color="primary" prepend-icon="tabler-plus"
            :to="{ name: 'notification-notification_id-guarantor-add', params: { notification_id: route.params.notification_id } }">
            Ajouter
          </VBtn>
          <VBtn :loading="loadings[3]" :disabled="loadings[3]" prepend-icon="tabler-refresh"
            @click="fetchGuarantors(); load(3)">
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
        :items="guarantorList" :items-length="totalGuarantor" class="text-no-wrap" @update:options="updateOptions">


        <template #item.observations="{ item }">
          <VList density="compact">
            <VListItem v-for="observation in item.observations">
              <VListItemTitle>
                <VChip label>
                  {{ observation }}
                </VChip>
              </VListItemTitle>
            </VListItem>
            <VListItem v-if="item.observations.length == 0" v-for="observation in ['Dossier complet']">
              <VListItemTitle>
                <VChip color="success" label>
                  {{ observation }}
                </VChip>
              </VListItemTitle>
            </VListItem>
          </VList>
        </template>

        <template #item.actions="{ item }">
          <IconBtn v-if="$can('read', 'guarantor')"
            :to="{ name: 'notification-notification_id-guarantor-id', params: { notification_id: route.params.notification_id, id: item.id } }">
            <VIcon icon="tabler-eye" />
          </IconBtn>
          <IconBtn v-if="$can('update', 'guarantor')"
            :to="{ name: 'notification-notification_id-guarantor-edit-id', params: { notification_id: route.params.notification_id, id: item.id } }">
            <VIcon icon="tabler-edit" />
          </IconBtn>
          <IconBtn v-if="$can('delete', 'guarantor')" @click="guarantorIdToDelete = item.id; isDialogVisible = true">
            <VIcon icon="tabler-trash" color='error' />
          </IconBtn>
          <VBtn icon variant="text" size="small" color="medium-emphasis">
            <VIcon size="24" icon="tabler-dots-vertical" />
            <VMenu activator="parent">
              <VList>
                <input ref="refInputEl" type="file" name="signed_notification" accept=".pdf,.png,.jpg" hidden
                  @input="uploadFile(item.id, $event)" />

                <div v-if="$can('download', 'guarantor')">
                  <!-- Télécharger billet à ordre non-signé -->
                  <VListItem
                    @click="downloadFile(`/api/guarantor/promissory-note/download/${item.id}`, `Billet-à-ordre-Caution-${item.notification.verbal_trial.committee_id}.docx`);">

                    <template #prepend>
                      <VIcon icon="tabler-download" />
                    </template>
                    <VListItemTitle>Télécharger Billet à ordre non signé</VListItemTitle>
                  </VListItem>

                  <!-- Télécharger billet à ordre signé -->
                  <VListItem v-if="item.signed_promissory_note_path"
                    @click="downloadFile(item.signed_promissory_note_path, `Billet-à-ordre-Caution-${item.signed_promissory_note_path.split('/').slice(-1)[0]}`)">

                    <template #prepend>
                      <VIcon icon="tabler-download" />
                    </template>
                    <VListItemTitle>Télécharger Billet à ordre signé</VListItemTitle>
                  </VListItem>
                </div>

                <div v-if="$can('upload', 'guarantor')">
                  <VDivider />
                  <!-- Ajouter Billet à ordre -->
                  <VListItem v-if="item.signed_promissory_note_path == null"
                    @click="uploadState = 'signed_promissory_note'; refInputEl?.click()">

                    <template #prepend>
                      <VIcon icon="tabler-cloud-upload" />
                    </template>
                    <VListItemTitle>Ajouter billet à ordre signé</VListItemTitle>
                  </VListItem>
                </div>
                <VDivider />
              </VList>
            </VMenu>
          </VBtn>
        </template>

        <template #bottom>
          <VDivider />

          <div class="d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3">
            <p class="text-sm text-medium-emphasis mb-0">
              {{ paginationMeta({ page, itemsPerPage }, totalGuarantor) }}
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

    <VDialog v-model="isDialogVisible" class="v-dialog-sm">
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

      <!-- Dialog Content -->
      <VCard title="Suppression">
        <VCardText>
          Etes vous sûr de vouloir supprimer cette caution?
        </VCardText>

        <VCardText class="d-flex justify-end gap-3 flex-wrap">
          <VBtn color="secondary" variant="tonal" @click="isDialogVisible = false">
            Annuler
          </VBtn>
          <VBtn @click="apiDelete(guarantorIdToDelete); isDialogVisible = false">
            Supprimer
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
