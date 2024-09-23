<!-- eslint-disable camelcase -->
<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'cat',
  },
})
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { paginationMeta } from '@api-utils/paginationMeta'
import JsFileDownloader from 'js-file-downloader'
import AppTextarea from '@/@core/components/app-form-elements/AppTextarea.vue';

const isDialogVisible = ref(false)
const catSelectedId = ref(0)
const selectedType = ref()
const searchQuery = ref('')
const loadings = ref([])
const itemsPerPage = ref(8)
const page = ref(1)
const isActionDialogVisible = ref(false)
const needComment = ref(false)
const actionTitle = ref("")
const actionText = ref("")
const actionButtonText = ref("")
const actionFunction = ref()
const actionComment = ref(null)
const headers = [
  {
    title: 'Numéro comitée',
    key: 'notification.verbal_trial.committee_id',
  },
  {
    title: 'Admin Crédit',
    key: 'notification.creator.full_name',
  },
  {
    title: 'Nom client',
    key: 'notification.verbal_trial.applicant_full_name',
  },
  {
    title: 'Secteur',
    key: 'sector',
  },
  {
    title: 'Numéro de prêt',
    key: 'credit_number',
  },
  {
    title: 'Montant',
    key: 'notification.verbal_trial.amount',
  },
  {
    title: 'Status',
    key: 'status',
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]
const typeList = {
  "company": 'Société',
  "individual_business": 'Entreprise Individuel',
  "particular": 'Particulier',
}

const {
  data: catData,
  execute: fetchCAT,
} = await useApi(createUrl('/cat', {
  query: {
    search: searchQuery,
    type: selectedType,
    page: page,
    with_creator: 1,
    with_verbal_trial: 1,
    with_notification: 1,
    has_notification: 1,
    is_simple: 0,
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
  await $api(`cat/${id}`, { method: 'DELETE' })
  fetchCAT()
}

const validateCAT = async id => {
  await $api(`cat/validate/${id}`, { method: 'PUT' })
  actionComment.value = null
  fetchCAT()
}

const unblockCAT = async id => {
  await $api(`cat/unblock/${id}`, { method: 'PUT' })
  actionComment.value = null
  fetchCAT()
}

const rejectValidationCAT = async id => {
  await $api(`cat/reject-validation/${id}`, { method: 'PUT', body: { comment: actionComment.value } })
  actionComment.value = null
  fetchCAT()
}

const rejectUnblockCAT = async id => {
  await $api(`cat/reject-unblock/${id}`, { method: 'PUT', body: { comment: actionComment.value } })
  actionComment.value = null
  fetchCAT()
}

const catList = computed(() => catData.value.data)
const totalCAT = computed(() => catData.value.total)
const lastPage = computed(() => catData.value.last_page)
</script>

<template>
  <div>
    <!-- 👉 widgets -->
    <VCard class="mb-6">
      <VCardText>
        <VRow>
          <VCardText>
            <h2>
              Liste des CAT
            </h2>
          </VCardText>
        </VRow>
      </VCardText>
    </VCard>

    <!-- 👉 cats -->
    <VCard class="mb-6">
      <VCardText>
        <div class="d-flex flex-wrap gap-4 mx-5">
          <div class="d-flex align-center">
            <!-- 👉 Search  -->
            <AppTextField v-model="searchQuery" placeholder="Rechercher un cat" density="compact"
              style="inline-size: 200px;" class="me-3" />
          </div>

          <VSpacer />
          <div class="d-flex gap-4 flex-wrap align-center">
            <!-- 👉 Export button -->
            <VBtn variant="tonal" color="secondary" prepend-icon="tabler-download">
              Exporter
            </VBtn>

            <VBtn v-if="$can('create', 'cat')" color="primary" prepend-icon="tabler-plus" :to="{ name: 'cat-add' }">
              Ajouter
            </VBtn>
            <VBtn :loading="loadings[3]" :disabled="loadings[3]" prepend-icon="tabler-refresh"
              @click="fetchCAT(); load(3)">
              Recharger
              <template #loader>
                <span class="custom-loader">
                  <VIcon icon="tabler-refresh" />
                </span>
              </template>
            </VBtn>
          </div>
        </div>
      </VCardText>

      <!-- 👉 Datatable  -->
      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :headers="headers" :items="catList"
        :items-length="totalCAT" class="text-no-wrap" @update:options="updateOptions">
        <template #item.type="{ item }">
          {{ typeList[item.type] }}
        </template>

        <template #item.status="{ item }">
          <VChip label :color="item.status.color">
            <VTooltip v-if="item.validation_comment && !unblock_comment" activator="parent"
              transition="scroll-x-transition" location="start">Raison: {{ item.validation_comment }}</VTooltip>
            <VTooltip v-if="item.unblock_comment" activator="parent" transition="scroll-x-transition" location="start">
              Raison: {{ item.unblock_comment }}</VTooltip>
            {{ item.status.message }}
          </VChip>
        </template>

        <template #item.comment="{ item }">
          {{ item.comment }}
        </template>

        <template #item.notification.verbal_trial.amount="{ item }">
          {{ String(item.notification.verbal_trial.amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} F CFA
        </template>

        <template #item.actions="{ item }">
          <span>
            <IconBtn v-if="$can('read', 'cat')" :to="{ name: 'cat-notification-id', params: { id: item.id } }">
              <VTooltip activator="parent" transition="scroll-x-transition" location="top">Details</VTooltip>
              <VIcon icon="tabler-eye" />
            </IconBtn>
            <VBtn icon variant="text" size="small" color="medium-emphasis">
              <VIcon size="24" icon="tabler-dots-vertical" />
              <VMenu activator="parent">
                <VList>
                  <VListItem v-if="$can('historical', 'pv') || $can('read', 'pv')"
                    :to="{ name: 'pv-id', params: { id: item.notification.verbal_trial.id } }">
                    <template #prepend>
                      <VIcon icon="tabler-eye" />
                    </template>

                    <VListItemTitle>Voir Pv</VListItemTitle>
                  </VListItem>
                  <VListItem v-if="$can('read', 'notification') || $can('historical', 'notification')"
                    :to="{ name: 'notification-id', params: { id: item.notification.id } }">
                    <template #prepend>
                      <VIcon icon="tabler-eye" />
                    </template>

                    <VListItemTitle>Voir la notification</VListItemTitle>
                  </VListItem>
                  <VListItem v-if="$can('download', 'cat')"
                    @click="downloadFile(`/api/cat/download/${item.id}`, `CAT-${item.notification.verbal_trial.committee_id}.docx`)">
                    <template #prepend>
                      <VIcon icon="tabler-download" />
                    </template>
                    <VListItemTitle>Télécharger CAT</VListItemTitle>
                  </VListItem>
                  <VDivider v-if="$can('validate', 'cat') && item.validation_status == 'waiting'" />
                  <VListItem v-if="$can('validate', 'cat') && item.validation_status == 'waiting'"
                    @click="catSelectedId = item.id; isActionDialogVisible = true; actionTitle = 'Valider CAT', actionText = 'Voulez vous vraiment valider ce CAT?', actionFunction = validateCAT; actionButtonText = 'Valider'; needComment = false">
                    <template #prepend>
                      <VIcon icon="tabler-check" />
                    </template>
                    <VListItemTitle>Valider CAT</VListItemTitle>
                  </VListItem>
                  <VListItem v-if="$can('reject_validation', 'cat') && item.validation_status == 'waiting'"
                    @click="catSelectedId = item.id; isActionDialogVisible = true; actionTitle = 'Rejeter CAT', actionText = 'Voulez vous vraiment rejeter ce CAT?', actionFunction = rejectValidationCAT; actionButtonText = 'Rejeter'; needComment = true">
                    <template #prepend>
                      <VIcon icon="tabler-x" />
                    </template>
                    <VListItemTitle>Rejeter CAT</VListItemTitle>
                  </VListItem>
                  <VDivider
                    v-if="$can('unblock', 'cat') && item.unblock_status == 'waiting' && item.validation_status == 'validated'" />
                  <VListItem
                    v-if="$can('unblock', 'cat') && item.unblock_status == 'waiting' && item.validation_status == 'validated'"
                    @click="catSelectedId = item.id; isActionDialogVisible = true; actionTitle = 'Débloquer CAT', actionText = 'Voulez vous vraiment débloquer ce CAT?', actionFunction = unblockCAT; actionButtonText = 'Débloquer'; needComment = false">
                    <template #prepend>
                      <VIcon icon="tabler-lock-open" />
                    </template>
                    <VListItemTitle>Débloquer CAT</VListItemTitle>
                  </VListItem>
                  <VListItem
                    v-if="$can('reject_unblock', 'cat') && item.unblock_status == 'waiting' && item.validation_status == 'validated'"
                    @click="catSelectedId = item.id; isActionDialogVisible = true; actionTitle = 'Rejeter deblocage CAT', actionText = 'Voulez vous vraiment rejeter le déblocage de ce CAT?', actionFunction = rejectUnblockCAT; actionButtonText = 'Rejeter'; needComment = true">
                    <template #prepend>
                      <VIcon icon="tabler-x" />
                    </template>
                    <VListItemTitle>Refuser déblocage CAT</VListItemTitle>
                  </VListItem>
                </VList>
              </VMenu>
            </VBtn>
          </span>
          <span>
            <VDivider />
            <IconBtn v-if="$can('update', 'cat')" :to="{ name: 'cat-notification-edit-id', params: { id: item.id } }"
              :disabled="item.validation_status == 'validated'">
              <VIcon icon="tabler-edit" />
            </IconBtn>
            <IconBtn v-if="$can('delete', 'cat')" @click="catSelectedId = item.id; isDialogVisible = true"
              :disabled="item.validation_status == 'validated'">
              <VIcon icon="tabler-trash" color='error' />
            </IconBtn>
          </span>

        </template>

        <template #bottom>
          <VDivider />

          <div class="d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3">
            <p class="text-sm text-medium-emphasis mb-0">
              {{ paginationMeta({ page, itemsPerPage }, totalCAT) }}
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

          <AppTextarea v-if="needComment" class="mt-3" v-model="actionComment" label="Commentaire"
            placeholder="Ex: RAS" />
        </VCardText>

        <VCardText class="d-flex justify-end gap-3 flex-wrap">
          <VBtn color="secondary" variant="tonal" @click="isActionDialogVisible = false">
            Annuler
          </VBtn>
          <VBtn @click="actionFunction(catSelectedId); isActionDialogVisible = false">
            {{ actionButtonText }}
          </VBtn>
        </VCardText>
      </VCard>
    </VDialog>


    <VDialog v-model="isDialogVisible" class="v-dialog-sm">
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

      <!-- Dialog De suppression -->
      <VCard title="Suppression">
        <VCardText>
          Etes vous sûr de vouloir supprimer ce CAT?
        </VCardText>

        <VCardText class="d-flex justify-end gap-3 flex-wrap">
          <VBtn color="secondary" variant="tonal" @click="isDialogVisible = false">
            Annuler
          </VBtn>
          <VBtn @click="apiDelete(catSelectedId); isDialogVisible = false">
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
