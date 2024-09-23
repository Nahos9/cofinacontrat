<!-- eslint-disable camelcase -->
<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'cat',
  },
})
const router = useRouter()
const route = useRoute('cat-id')

const sourceOfReimbursement = {
  "revenue_from_the_activity": "Recettes de l’activité",
  "final_payer_settlement": "Règlement du payeur final",
  "resale_of_goods": "Reventes des marchandise",
}


const {
  data: cat,
} = await useApi(createUrl(`/cat/${route.params.id}`, {
  query: {
    with_verbal_trial: 1
  },
}))


if (cat.value.status == 200) {
  cat.value = cat.value.data.c_a_t
} else {
  router.push("/cat")
}

const tableData = [
  { "title": "N° Comité", "value": cat.value.notification.verbal_trial.committee_id },
  { "title": "Numéro de prêt", "value": cat.value.credit_number },
  { "title": "Secteur", "value": cat.value.sector },
  { "title": "Prière échéance", "value": cat.value.first_deadline },
  { "title": "Dernière échéance", "value": cat.value.last_deadline },
  { "title": "Source du remboursement", "value": sourceOfReimbursement[cat.value.source_of_reimbursement] },
  { "title": "Instructions du département risque et crédit", "value": cat.value.instructions_from_the_risk_and_credit_department },
  { "title": "Encours à solder", "value": cat.value.outstanding_number_ready_to_settle },
  { "title": "Autres frais", "value": String(cat.value.other_expenses).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "TEG", "value": String(cat.value.teg).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
]
</script>

<template>
  <section v-if="cat">
    <VRow>
      <VCol cols="12">
        <VCard>
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="10">
              <VBtn :to="{ name: 'cat' }">
                <VIcon icon="tabler-arrow-left" />
                CATs
              </VBtn>
            </VCol>
            <VCol cols="2" class="text-right">
              <VBtn append-icon="tabler-edit" :to="{ name: 'cat-edit-id', params: { id: cat.id } }">
                Modifier
              </VBtn>
            </VCol>
            <VCol cols="12">
              <h2 class="text-center">
                CAT N°{{ cat.notification.verbal_trial.committee_id }}
              </h2>
            </VCol>
            <VCol cols="12">
              <h2>Informations:</h2>
            </VCol>
            <VCol cols="12">
              <VTable class="text-no-wrap">
                <tbody>
                  <tr v-for="item in tableData" :key="item.key">
                    <td colspan="5">
                      {{ item.title }}
                    </td>
                    <td colspan="1">
                      {{ item.value }}
                    </td>
                  </tr>
                </tbody>
              </VTable>
            </VCol>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </section>
</template>

<style lang="scss">
.invoice-preview-table {
  --v-table-row-height: 44px !important;
}

@media print {
  .v-theme--dark {
    --v-theme-surface: 255, 255, 255;
    --v-theme-on-surface: 94, 86, 105;
  }

  body {
    background: none !important;
  }

  @page {
    margin: 0;
    size: auto;
  }

  .layout-page-content,
  .v-row,
  .v-col-md-9 {
    padding: 0;
    margin: 0;
  }

  .product-buy-now {
    display: none;
  }

  .v-navigation-drawer,
  .layout-vertical-nav,
  .app-customizer-toggler,
  .layout-footer,
  .layout-navbar,
  .layout-navbar-and-nav-container {
    display: none;
  }

  .v-card {
    box-shadow: none !important;

    .print-row {
      flex-direction: row !important;
    }
  }

  .layout-content-wrapper {
    padding-inline-start: 0 !important;
  }

  .v-table__wrapper {
    overflow: hidden !important;
  }
}
</style>
