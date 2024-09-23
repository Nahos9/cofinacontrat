<!-- eslint-disable camelcase -->

<script setup>
definePage({
  meta: {
    action: 'without-signed-notification',
    subject: 'simple-notification',
  },
})
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { paginationMeta } from '@api-utils/paginationMeta'
import JsFileDownloader from 'js-file-downloader'
import { $api } from '@/utils/api';
import { useRouter } from 'vue-router';

const router = useRouter()
const selectedType = ref()
const searchQuery = ref('')
const refInputEl = ref()
const uploadState = ref('signed_notification')
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
    title: 'Numéro comitée',
    key: 'verbal_trial.committee_id',
  },
  {
    title: 'Admin Crédit',
    key: 'creator.full_name',
  },
  {
    title: 'Nom client',
    key: 'verbal_trial.applicant_full_name',
  },
  {
    title: 'Téléphone',
    key: 'representative_phone_number',
  },
  {
    title: 'Montant',
    key: 'verbal_trial.amount',
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

const {
  data: notificationData,
  execute: fetchNotifications,
} = await useApi(createUrl('/notification', {
  query: {
    search: searchQuery,
    type: selectedType,
    page: page,
    with_type_of_credit: 1,
    with_company: 1,
    with_individual_business: 1,
    with_creator: 1,
    head_credit_validation: 'v',
    has_cat: 0,
    is_simple: 1,
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



const typeList = {
  "company": 'Société',
  "individual_business": 'Entreprise Individuel',
  "particular": 'Particulier',
}

const downloadFile = async (url, fileName) => {
  try {
    new JsFileDownloader({
      url: url,
      headers: [
        { name: 'Authorization', value: `Bearer ${useCookie('userToken').value}` },
        { name: 'Accept', value: `application/json` },
      ],
      nameCallback: function (name) {
        return fileName
      },
    })
    fetchNotifications()
  } catch (error) {
    console.error('Erreur lors du téléchargement:', error)
  }
}

const apiChangeStatus = async id => {
  await $api(`notification/change-status/${id}`, { method: 'PUT', body: { status: actionStatus.value, comment: actionComment.value } })
  actionComment.value = ""
  if (actionStatus.value == "validated") {
    router.push(`/cat/simple-notification/add?id=${id}`)
  }
  fetchNotifications()
}

const apiSendNotification = async id => {
  await $api(`notification/send/${id}`, { method: 'PUT' })
  fetchNotifications()
}


const uploadFile = async (id, event) => {
  const { files } = event.target;
  if (files && files.length === 1) {
    const reader = new FileReader();
    reader.onload = async () => {
      const base64Image = reader.result;
      try {
        const response = await fetch(`/api/notification/upload/${id}`, {
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
          console.log('Document envoyé avec succès.');
          fetchNotifications();
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

const notificationList = computed(() => notificationData.value.data)
const totalPv = computed(() => notificationData.value.total)
const lastPage = computed(() => notificationData.value.last_page)
</script>

<template>
  <div>
    <VCard class="mb-6">
      <VCardText>
        <VRow>
          <VCardText>
            <h2>
              Liste des contrats
            </h2>
          </VCardText>
        </VRow>
      </VCardText>
    </VCard>

    <VCard class="mb-6">
      <div class="d-flex flex-wrap gap-4 mt-4 mx-5">
        <div class="d-flex align-center">
          <!-- <AppTextField v-model="searchQuery" placeholder="Rechercher un contrat" density="compact"
            style="inline-size: 200px;" class="me-3" /> -->
        </div>

        <VSpacer />
        <div class="d-flex gap-4 flex-wrap align-center">
          <VBtn variant="tonal" color="secondary" prepend-icon="tabler-download">
            Export
          </VBtn>

          <VBtn v-if="$can('create', 'notification')" color="primary" prepend-icon="tabler-plus"
            :to="{ name: 'simple-notification-add' }">
            Ajouter
          </VBtn>
          <VBtn :loading="loadings[3]" :disabled="loadings[3]" prepend-icon="tabler-refresh"
            @click="fetchNotifications(); load(3)">
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


      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :headers="headers"
        :items="notificationList" :items-length="totalPv" class="text-no-wrap" @update:options="updateOptions">

        <template #item.type="{ item }">
          {{ typeList[item.type] }}
        </template>

        <template #item.verbal_trial.amount="{ item }">
          {{ String(item.verbal_trial.amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} F CFA
        </template>

        <template #item.observations="{ item }">
          <VList density=" compact">
            <VListItem v-if="item.observations.length > 0" v-for=" observation in item.observations">
              <VListItemTitle>
                <VChip label>
                  {{ observation }}
                </VChip>
              </VListItemTitle>
            </VListItem>
            <VListItem v-if="item.observations.length == 0">
              <VChip label
                :color="{ 'validated': 'success', 'rejected': 'error', 'waiting': (item.sent) ? 'warning' : 'success' }[item.status]">
                <VTooltip v-if="item.status" activator="parent" transition="scroll-x-transition" location="start">
                  Raison: {{ item.status_observation }}</VTooltip>
                {{ (item.status == 'validated') ? 'Dossier validé' : null }}
                {{ (item.status == 'waiting') ? (item.sent) ? 'Dossier en attente de validation' : 'Dossier prêt'
            : null }}
                {{ (item.status == 'rejected') ? 'Dossier rejeté' : null }}
              </VChip>
            </VListItem>
          </VList>
        </template>

        <template #item.actions="{ item }">
          <span>
            <IconBtn :to="{ name: 'simple-notification-id', params: { id: item.id } }">
              <VTooltip activator="parent" transition="scroll-x-transition" location="start">Details</VTooltip>
              <VIcon icon="tabler-eye" />
            </IconBtn>
            <VBtn icon variant="text" size="small" color="medium-emphasis">
              <VIcon size="24" icon="tabler-dots-vertical" />
              <VMenu activator="parent">
                <VList>
                  <input ref="refInputEl" type="file" name="signed_notification" accept=".pdf,.png,.jpg" hidden
                    @input="uploadFile(item.id, $event)" />

                  <VBadge v-if="$can('read', 'guarantor')" inline :content="item.guarantors_count">
                    <VListItem
                      :to="{ name: 'simple-notification-notification_id-guarantor', params: { notification_id: item.id } }">
                      <template #prepend>
                        <VIcon icon="tabler-users" />
                      </template>

                      <VListItemTitle>
                        Voir les Cautions
                      </VListItemTitle>
                    </VListItem>
                  </VBadge>
                  <VListItem v-if="$can('read', 'pv')" :to="{ name: 'pv-id', params: { id: item.verbal_trial.id } }">

                    <template #prepend>
                      <VIcon icon="tabler-eye" />
                    </template>

                    <VListItemTitle>Voir le Pv</VListItemTitle>
                  </VListItem>


                  <span v-if="$can('download', 'notification')">
                    <span>
                      <VDivider />
                      <!-- Télécharger notification non-signé -->
                      <VListItem
                        @click="downloadFile(`/api/notification/download/${item.id}`, `Notification-${item.verbal_trial.committee_id}.docx`);">
                        <template #prepend>
                          <VIcon icon="tabler-download" />
                        </template>
                        <VListItemTitle>Télécharger Notification non signé</VListItemTitle>
                      </VListItem>
                    </span>

                    <span>
                      <VDivider />
                      <!-- Télécharger notification signé -->
                      <VListItem v-if="item.signed_notification_path"
                        @click="downloadFile(item.signed_notification_path, `Notification-${item.signed_notification_path.split('/').slice(-1)[0]}`)">

                        <template #prepend>
                          <VIcon icon="tabler-download" />
                        </template>
                        <VListItemTitle>Télécharger Notification signé</VListItemTitle>
                      </VListItem>
                    </span>
                  </span>

                  <span v-if="$can('upload', 'notification')">
                    <!-- Ajouter Notification signé -->
                    <VListItem v-if="item.signed_notification_path == null || item.status == 'rejected'"
                      @click="uploadState = 'signed_notification'; refInputEl?.click()">

                      <template #prepend>
                        <VIcon icon="tabler-cloud-upload" />
                      </template>
                      <VListItemTitle color="error">Ajouter notification signé</VListItemTitle>
                    </VListItem>
                  </span>
                </VList>
              </VMenu>
            </VBtn>
          </span>
          <span v-if="item.observations.length == 0 && $can('send', 'notification')">
            <VDivider />
            <VRow>
              <VCol col="12" class="text-center">
                <IconBtn v-if="!item.sent"
                  @click="selectedItemId = item.id; actionTitle = 'Envoyer le dossier de la notification', actionText = 'Voulez vous vraiment envoyer le dossier de cette notification?', actionFunction = apiSendNotification; actionButtonText = 'Envoyer'; commentPresence = false; isActionDialogVisible = true;">
                  <VTooltip activator="parent" transition="scroll-x-transition" location="start">Envoyer</VTooltip>
                  <VIcon icon="tabler-send" color="success" />
                </IconBtn>
              </VCol>
            </VRow>
          </span>
          <span
            v-if="item.sent && (($can('reject', 'pv') && item.status != 'rejected' && item.observations.length == 0) || ($can('validate', 'pv') && item.status == 'waiting' && item.observations.length == 0) || ($can('create', 'cat') && item.status == 'validated'))">
            <VDivider />
            <IconBtn v-if="$can('reject', 'pv') && item.status != 'rejected' && item.observations.length == 0"
              @click="selectedItemId = item.id; actionTitle = 'Rejeter la notification', actionText = 'Voulez vous vraiment rejeter cette notification?', actionFunction = apiChangeStatus; actionButtonText = 'Rejeter'; commentPresence = true; actionStatus = 'rejected'; isActionDialogVisible = true;">
              <VTooltip activator="parent" transition="scroll-x-transition" location="start">Rejeter</VTooltip>
              <VIcon icon="tabler-x" color="error" />
            </IconBtn>
            <IconBtn v-if="$can('validate', 'pv') && item.status == 'waiting' && item.observations.length == 0"
              @click="selectedItemId = item.id; actionTitle = 'Valider la notification', actionText = 'Voulez vous vraiment valider cette notification?', actionFunction = apiChangeStatus; actionButtonText = 'Valider'; commentPresence = false; actionStatus = 'validated'; isActionDialogVisible = true;">
              <VTooltip activator="parent" transition="scroll-x-transition" location="end">Valider</VTooltip>
              <VIcon icon="tabler-check" color="success" />
            </IconBtn>
            <IconBtn v-if="$can('create', 'cat') && item.status == 'validated'"
              :to="{ name: 'cat-simple-notification-add', query: { id: item.id } }">
              <VTooltip activator="parent" transition="scroll-x-transition" location="end">Créer le CAT</VTooltip>
              <VIcon icon="tabler-file-plus" color="success" />
            </IconBtn>
          </span>
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
