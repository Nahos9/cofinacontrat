<!-- eslint-disable camelcase -->

<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'contract',
  },
})
const router = useRouter()
const route = useRoute("contract-id")

let backRoute = "/contract"

const frenchMensuality = {
  "mensual": "Mensuelle",
  "quarterly": "Trimestrielle",
  "semi-annual": "Semestrielle",
  "annual": "Annuel",
  "in-fine": "À la fin",
}

const documentTypeList = {
  "cni": 'Carte d\'identité nationale',
  "passport": 'Passeport',
  "residence_certificate": 'Certificat de résidence',
  "driving_licence": 'Permis de conduire',
  "carte_sej": 'carte de séjour',
}

const garanteeTypeList = {
  "vehicle": "Véhicule",
  "stock": "Stock"
}

const {
  data: contract,
} = await useApi(createUrl(`/contract/${route.params.id}`, {
  query: {
    with_type_of_credit: 1,
    with_caf: 1,
    with_type_of_guarantees: 1,
    with_pledges: 1,
  },
}))

if (contract.value.status == 200) {
  contract.value = contract.value.data.contract
} else {
  router.push("/contract")
}

const tableData = [
  { "title": "Montant", "value": String(contract.value.verbal_trial.amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Durée", "value": contract.value.verbal_trial.duration + " mois" },
  { "title": "Périodicité", "value": frenchMensuality[contract.value.verbal_trial.periodicity] },
  { "title": "Taux d'intérêt HT", "value": contract.value.verbal_trial.tax_fee_interest_rate + "%" },
  // { "title": "TAF", "value": contract.value.verbal_trial.taf + "%" },
  { "title": "Echéance TTC", "value": String(contract.value.verbal_trial.due_amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Frais de dossier", "value": String((contract.value.verbal_trial.amount * contract.value.verbal_trial.administrative_fees_percentage) / 100).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Prime d'assurance", "value": String(contract.value.verbal_trial.insurance_premium).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
]

if (contract.value.verbal_trial.duration > 13) {
  tableData.push({ "title": "Prime de révision de ligne", "value": "1% du capital restant dû après 13 mois" })
}
if (contract.value.observations.length == 0) {
  backRoute = "/contract/historical"
}
</script>

<template>
  <section v-if="contract">
    <VRow>
      <VCol cols="12">
        <VCard>
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="10">
              <VBtn :to="backRoute">
                <VIcon icon="tabler-arrow-left" />
                Contrats
              </VBtn>
            </VCol>
            <VCol cols="2" class="text-right">
              <VBtn append-icon="tabler-edit" :to="{ name: 'contract-edit-id', params: { id: route.params.id } }"
                :disabled="contract.status == 'validated'">
                Modifier
              </VBtn>
            </VCol>
            <VCol>
              <VAlert v-if="contract.status == 'rejected' && contract.status_observation" color="warning">
                Motif du refus: {{ contract.status_observation }}
              </VAlert>
            </VCol>
            <VCol cols="12">
              <h2 class="text-center">
                Contrat N°{{ contract.verbal_trial.committee_id }}
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
              <!-- <p style="font-size: 20px">
                Date de validation: {{ contract.verbal_trial.created_at }}
              </p> -->
            </VCol>
            <VCol cols="6">
              <p style="font-size: 20px">
                : {{ contract.verbal_trial.caf.full_name }}
              </p>
              <p style="font-size: 20px">
                : <strong> {{ contract.verbal_trial.applicant_last_name + " " +
    contract.verbal_trial.applicant_first_name
                  }}</strong>
              </p>
              <p style="font-size: 20px">
                : {{ contract.verbal_trial.account_number }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.verbal_trial.activity }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.verbal_trial.purpose_of_financing }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.verbal_trial.type_of_credit.name }}
              </p>
            </VCol>
          </VCardText>

          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="12">
              <h2>Informations suplémentaires du client</h2>
            </VCol>
            <VCol cols="6">
              <p style="font-size: 20px">
                Date de naissance
              </p>
              <p style="font-size: 20px">
                Lieu de naissance
              </p>
              <p style="font-size: 20px">
                Nationnalité
              </p>
              <p style="font-size: 20px">
                Addresse du domicile
              </p>
              <p style="font-size: 20px">
                Type de la pièce d'identité
              </p>
              <p style="font-size: 20px">
                Numéro de la pièce d'identité
              </p>
              <p style="font-size: 20px">
                Date de délivrance de la pièce d'identité
              </p>
              <p style="font-size: 20px">
                Numéro de téléphone
              </p>
            </VCol>
            <VCol cols="6">
              <p style="font-size: 20px">
                : {{ contract.representative_birth_date_fr }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.representative_birth_place }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.representative_nationality }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.representative_home_address }}
              </p>
              <p style="font-size: 20px">
                : {{ documentTypeList[contract.representative_type_of_identity_document] }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.representative_number_of_identity_document }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.representative_date_of_issue_of_identity_document_fr }}
              </p>
              <p style="font-size: 20px">
                : {{ contract.representative_phone_number }}
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

          <VCardText v-if="contract.has_pledges == '1'"
            class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="12">
              <h2>Gages</h2>
            </VCol>
            <VCol cols="12">
              <VTable class="text-no-wrap">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Commentaire</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="item in contract.pledges" :key="item.key">
                    <td>
                      {{ garanteeTypeList[item.type] }}
                    </td>
                    <td>
                      {{ item.comment }}
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
                <li v-for="(item, index) in contract.verbal_trial.guarantees" :key="index" style="font-size: 20px">
                  {{ item.type_of_guarantee.name }} 
                  : {{ item.comment }}
                </li>
              </ul>
              </p>
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
