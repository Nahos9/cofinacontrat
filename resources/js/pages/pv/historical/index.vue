<!-- eslint-disable camelcase -->

<script setup>
definePage({
  meta: {
    action: 'historical',
    subject: 'pv',
  },
})

import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { paginationMeta } from '@api-utils/paginationMeta'
import AppAutocomplete from '@/@core/components/app-form-elements/AppAutocomplete.vue';
import JsFileDownloader from 'js-file-downloader'


const isDialogVisible = ref(false)
const idToDelete = ref(0)
const type_of_credit_id = ref()
const searchQuery = ref('')

const headers = [
  {
    title: 'Numéro comitée',
    key: 'committee_id',
  },
  {
    title: 'Prénom client',
    key: 'applicant_first_name',
  },
  {
    title: 'Nom client',
    key: 'applicant_last_name',
  },
  {
    title: 'Type Credit',
    key: 'type_of_credit.full_name',
  },
  {
    title: 'Montant',
    key: 'amount_fr',
  },
  {
    title: 'Durée',
    key: 'duration',
  },
  {
    title: 'CAF',
    key: 'caf.full_name',
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
  data: pvData,
  execute: fetchPv,
} = await useApi(createUrl('/verbal-trial', {
  query: {
    search: searchQuery,
    type_of_credit_id: type_of_credit_id,
    page: page,
    has_contract: 1,
    has_mortgage: 0,
    status: 'v',
    with_caf: 1,
    with_type_of_credit: 1,
  },
}))

const {
  data: type_of_credit_list_data,
} = await useApi(createUrl('/type-of-credit', {
  query: {
    paginate: 0,
  },
}))

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
  fetchPv()
}

const pvList = computed(() => pvData.value.data)
const totalPv = computed(() => pvData.value.total)
const lastPage = computed(() => pvData.value.last_page)
const type_of_credit_list = computed(() => type_of_credit_list_data.value.data)

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
              Historique des procès verbaux
            </h2>
          </VCardText>
        </VRow>
      </VCardText>
    </VCard>

    <!-- 👉 pvs -->
    <VCard title="Filtres" class="mb-6">
      <VCardText>
        <VRow>
          <!-- 👉 Select Status -->
          <VCol cols="12" sm="4">
            <AppAutocomplete v-model="type_of_credit_id" placeholder="Type de crédit" item-title="full_name"
              item-value="id" :items="type_of_credit_list" clearable clear-icon="tabler-x" />
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

          <VBtn v-if="$can('create', 'pv')" color="primary" prepend-icon="tabler-plus" :to="{ name: 'pv-add' }">
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
      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :headers="headers" :items="pvList"
        :items-length="totalPv" class="text-no-wrap" @update:options="updateOptions">
        <!-- Actions -->

        <template #item.actions="{ item }">
          <IconBtn v-if="$can('read', 'pv') || $can('historical', 'pv')"
            :to="{ name: 'pv-id', params: { id: item.id } }">
            <VTooltip activator="parent" transition="scroll-x-transition" location="top">Details</VTooltip>
            <VIcon icon=" tabler-eye" />
          </IconBtn>
          <IconBtn v-if="$can('download', 'pv')"
            @click="downloadFile(`/api/verbal-trial/download/${item.id}`, `PV-${item.committee_id}.docx`)">
            <VTooltip activator="parent" transition="scroll-x-transition" location="top">Télécharger</VTooltip>
            <VIcon icon="tabler-download" />
          </IconBtn>
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
    <VDialog v-model="isDialogVisible" class="v-dialog-sm">
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

      <!-- Dialog Content -->
      <VCard title="Suppression">
        <VCardText>
          Etes vous sûr de vouloir supprimer ce pv?
        </VCardText>

        <VCardText class="d-flex justify-end gap-3 flex-wrap">
          <VBtn color="secondary" variant="tonal" @click="isDialogVisible = false">
            Annuler
          </VBtn>
          <VBtn @click="apiDelete(idToDelete); isDialogVisible = false">
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
