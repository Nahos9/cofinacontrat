<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'pv',
  },
})
const router = useRouter()
const route = useRoute("pv-id")
let nextRoute = "/pv";

const frenchMensuality = {
  "mensual": "Mensuelle",
  "quarterly": "Trimestrielle",
  "semi-annual": "Semestrielle",
  "annual": "Annuel",
  "in-fine": "À la fin",
}

const { data: verbalTrial } = await useApi(`/verbal-trial/${Number(route.params.id)}?with_caf=1&with_type_of_credit=1&with_type_of_guarantees=1`)

if (verbalTrial.value.status == 200) {
  verbalTrial.value = verbalTrial.value.data.verbalTrial
} else {
  router.push("/pv")
}

const tableData = [
  { "title": "Montant", "value": String(verbalTrial.value.amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Durée", "value": verbalTrial.value.duration + " mois" },
  { "title": "Périodicité", "value": frenchMensuality[verbalTrial.value.periodicity] },
  { "title": "Taux d'intérêt HT", "value": verbalTrial.value.tax_fee_interest_rate + "%" },
  { "title": "TAF", "value": verbalTrial.value.taf + "%" },
  { "title": "Echéance TTC", "value": String(verbalTrial.value.due_amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Frais de dossier", "value": String((verbalTrial.value.amount * verbalTrial.value.administrative_fees_percentage) / 100).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Prime d'assurance", "value": String(verbalTrial.value.insurance_premium).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
]

if (verbalTrial.value.duration > 13) {
  tableData.push({ "title": "Prime de révision de ligne", "value": "1% du capital restant dû après 13 mois" })
}

verbalTrial.value.guarantees.forEach(guarantee => {
  if (guarantee.type_of_guarantee_id == 9) {
    nextRoute = '/pv/without-notification'
  }
})
</script>

<template>
  <section v-if="verbalTrial">
    <VRow>
      <VCol cols="12">
        <VCard>
          <!-- SECTION Header -->
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="10">
              <VBtn prepend-icon="tabler-arrow-narrow-left" :to="nextRoute"
                :disabled="verbalTrial.status == 'vaidated'">
                Procès verbaux
              </VBtn>
            </VCol>
            <VCol cols="2" class="text-right">
              <VBtn append-icon="tabler-edit" :to="{ name: 'pv-edit-id', params: { id: verbalTrial.id } }"
                :disabled="verbalTrial.status == 'validated'">
                Modifier
              </VBtn>
            </VCol>
            <VCol v-if="verbalTrial.status == 'rejected' && verbalTrial.status_observation">
              <VAlert color="warning">
                Motif du refus: {{ verbalTrial.status_observation }}
              </VAlert>
            </VCol>
            <VCol cols="12">
              <h2 class="text-center">
                Procès Verbal N°{{ verbalTrial.committee_id }}
              </h2>
            </VCol>
            <VCol cols="6">
              <p style="font-size: 20px">
                CAF
              </p>
              <p style="font-size: 20px">
                Emprunteur
              </p>
              <p style="font-size: 20px">
                N° de compte
              </p>
              <p style="font-size: 20px">
                Activité
              </p>
              <p style="font-size: 20px">
                Objet du financement
              </p>
              <p style="font-size: 20px">
                Type de concours solicité
              </p>
              <br>
              <p style="font-size: 20px">
                Date de validation: {{ verbalTrial.created_at_fr }}
              </p>
            </VCol>
            <VCol cols="6">
              <p style="font-size: 20px">
                : {{ verbalTrial.caf.full_name }}
              </p>
              <p style="font-size: 20px">
                : <strong> {{ verbalTrial.applicant_last_name + " " + verbalTrial.applicant_first_name
                  }}</strong>
              </p>
              <p style="font-size: 20px">
                : {{ verbalTrial.account_number }}
              </p>
              <p style="font-size: 20px">
                : {{ verbalTrial.activity }}
              </p>
              <p style="font-size: 20px">
                : {{ verbalTrial.purpose_of_financing }}
              </p>
              <p style="font-size: 20px">
                : {{ verbalTrial.type_of_credit.name }}
              </p>
            </VCol>
          </VCardText>

          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="12">
              <h2>CARACTERISTIQUES</h2>
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

          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="12">
              <h2>GARANTIES A RECUEILLIR</h2>
            </VCol>
            <VCol cols="12">
              <p>
              <ul>
                <li v-for="(item, index) in verbalTrial.guarantees" :key="index" style="font-size: 20px">
                  {{ item.type_of_guarantee.name }} de {{ String(item.value).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }} F
                  CFA : {{ item.comment }}
                </li>
              </ul>
              </p>
            </VCol>
          </VCardText>

          <VDivider />
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
