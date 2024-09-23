<!-- eslint-disable camelcase -->

<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'contract',
  },
})
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { paginationMeta } from '@api-utils/paginationMeta'
import JsFileDownloader from 'js-file-downloader'
import { $api } from '@/utils/api';

const isDialogVisible = ref(false)
const contractIdToDelete = ref(0)
const selectedType = ref()
const searchQuery = ref('')

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
    title: 'Type de contrat',
    key: 'type',
  },
  {
    title: 'Montant',
    key: 'verbal_trial.amount',
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
  data: notificationData,
  execute: fetchContracts,
} = await useApi(createUrl('/notification', {
  query: {
    search: searchQuery,
    type: selectedType,
    page: page,
    with_verbal_trial: 1,
    // with_type_of_credit: 1,
    // with_creator: 1,
    // has_upload_completed: 1,
    // has_cat: 1,
    // is_simple: 0,
    // status: 'v',
  },
}))

const notificationList = computed(() => notificationData.value.data)
const totalPv = computed(() => notificationData.value.total)
const lastPage = computed(() => notificationData.value.last_page)

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
    fetchContracts()
  } catch (error) {
    console.error('Erreur lors du téléchargement:', error)
  }
}

const apiDelete = async id => {
  await $api(`notification/${id}`, { method: 'DELETE' })
  fetchContracts()
}

</script>

<template>
  <div>
    <VCard class="mb-6">
      <VCardText>
        <VRow>
          <VCardText>
            <h2>
              Liste des contrats en attente de CAT
            </h2>
          </VCardText>
        </VRow>
      </VCardText>
    </VCard>

    <VCard title="Filtres" class="mb-6">
      <VCardText>
        <VRow>
          <VCol cols="12" sm="4">
            <AppSelect v-model="selectedType" placeholder="Type de contrat"
              :items="[{ value: 'company', title: 'Société' }, { value: 'particular', title: 'Particulier' }, { value: 'individual_business', title: 'Entreprise Individuel' }]"
              clearable clear-icon="tabler-x" />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider class="my-4" />

      <div class="d-flex flex-wrap gap-4 mx-5">
        <div class="d-flex align-center">
          <AppTextField v-model="searchQuery" placeholder="Rechercher un contrat" density="compact"
            style="inline-size: 200px;" class="me-3" />
        </div>

        <VSpacer />
        <div class="d-flex gap-4 flex-wrap align-center">
          <VBtn variant="tonal" color="secondary" prepend-icon="tabler-download">
            Export
          </VBtn>

          <VBtn v-if="$can('create', 'notification')" color="primary" prepend-icon="tabler-plus"
            :to="{ name: 'notification-add' }">
            Ajouter
          </VBtn>
          <VBtn :loading="loadings[3]" :disabled="loadings[3]" prepend-icon="tabler-refresh"
            @click="fetchContracts(); load(3)">
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

        <template #item.actions="{ item }">
          <IconBtn :to="{ name: 'notification-id', params: { id: item.id } }">
            <VIcon icon="tabler-eye" />
          </IconBtn>
          <VBtn icon variant="text" size="small" color="medium-emphasis">
            <VIcon size="24" icon="tabler-dots-vertical" />
            <VMenu activator="parent">
              <VList>

                <VBadge v-if="$can('read', 'guarantor')" inline :content="item.guarantors_count">
                  <VListItem
                    :to="{ name: 'notification-notification_id-guarantor', params: { notification_id: item.id } }">
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


                <div v-if="$can('download', 'notification')">
                  <VDivider />
                  <!-- Télécharger contrat non-signé -->
                  <VListItem
                    @click="downloadFile(`/api/notification/download/${item.id}`, `Notification-${item.verbal_trial.committee_id}.docx`)">

                    <template #prepend>
                      <VIcon icon="tabler-download" />
                    </template>
                    <VListItemTitle>Télécharger Contrat non-signé</VListItemTitle>
                  </VListItem>
                  <!-- Télécharger billet à ordre non-signé -->
                  <VListItem
                    @click="downloadFile(`/api/contract/promissory-note/download/${item.id}`, `Billet-à-ordre-${item.verbal_trial.committee_id}.docx`);">

                    <template #prepend>
                      <VIcon icon="tabler-download" />
                    </template>
                    <VListItemTitle>Télécharger Billet à ordre non signé</VListItemTitle>
                  </VListItem>
                  <VDivider />
                  <!-- Télécharger contrat signé -->
                  <VListItem v-if="item.signed_contract_path"
                    @click="downloadFile(item.signed_contract_path, `Contrat-${item.signed_contract_path.split('/').slice(-1)[0]}`)">

                    <template #prepend>
                      <VIcon icon="tabler-download" />
                    </template>
                    <VListItemTitle>Télécharger Contrat signé</VListItemTitle>
                  </VListItem>
                  <!-- Télécharger billet à ordre signé -->
                  <VListItem v-if="item.signed_promissory_note_path"
                    @click="downloadFile(item.signed_promissory_note_path, `Billet-à-ordre-${item.signed_promissory_note_path.split('/').slice(-1)[0]}`)">

                    <template #prepend>
                      <VIcon icon="tabler-download" />
                    </template>
                    <VListItemTitle>Télécharger Billet à ordre signé</VListItemTitle>
                  </VListItem>
                </div>

              </VList>
            </VMenu>
          </VBtn>
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
