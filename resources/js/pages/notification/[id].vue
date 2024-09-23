<script setup>
definePage({
  meta: {
    action: ['read'],
    subject: 'notification',
  },
})
const router = useRouter()
const route = useRoute("notification-id")
let backRouteName = 'notification'
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
}
const notificationTypeList = {
  "company": "Société",
  "individual_business": "Entreprise individuelle",
  "particular": "Particulier"
}

const {
  data: notification,
} = await useApi(createUrl(`/notification/${route.params.id}`, {
  query: {
    with_type_of_credit: 1,
    with_caf: 1,
    with_creator: 1,
    with_verbal_trial: 1,
    with_type_of_guarantees: 1,
  },
}))

if (notification.value.status == 200) {
  notification.value = notification.value.data.notification
} else {
  router.push({ name: "pv-without-notification" })
}

const tableData = [
  { "title": "Montant", "value": String(notification.value.verbal_trial.amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Durée", "value": notification.value.verbal_trial.duration + " mois" },
  { "title": "Périodicité", "value": frenchMensuality[notification.value.verbal_trial.periodicity] },
  { "title": "Taux d'intérêt HT", "value": notification.value.verbal_trial.tax_fee_interest_rate + "%" },
  { "title": "TAF", "value": notification.value.verbal_trial.taf + "%" },
  { "title": "Echéance TTC", "value": String(notification.value.verbal_trial.due_amount).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Frais de dossier", "value": String((notification.value.verbal_trial.amount * notification.value.verbal_trial.administrative_fees_percentage) / 100).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
  { "title": "Prime d'assurance", "value": String(notification.value.verbal_trial.insurance_premium).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + "F CFA" },
]
const notificationData = [
  { "title": "Téléphone", "value": notification.value.representative_phone_number },
  { "title": "Adresse", "value": notification.value.representative_home_address },
  { "title": "Nombre d'écheance", "value": notification.value.number_of_due_dates },
  { "title": "Prime de risque", "value": notification.value.risk_premium_percentage + "%" },
  { "title": "Montant total des intérêts", "value": notification.value.total_amount_of_interest },
  { "title": "Type de pièce d'identité", "value": documentTypeList[notification.value.representative_type_of_identity_document] },
  { "title": "Numéro de la pièce d'identité", "value": notification.value.representative_number_of_identity_document },
  { "title": "Date d'expiration de la pièce d'identité", "value": notification.value.representative_date_of_issue_of_identity_document },
  { "title": "Type de notification", "value": notificationTypeList[notification.value.type] },
]
if (notification.value.verbal_trial.duration > 13) {
  tableData.push({ "title": "Prime de révision de ligne", "value": "1% du capital restant dû après 13 mois" })
}
if (notification.value.head_credit_validation == 'validated') {
  if (notification.value.status == 'validated') {
    backRouteName = 'notification-historical'
  } else {
    backRouteName = 'notification-without-signed-contract'
  }
}
</script>

<template>
  <section v-if="notification">
    <VRow>
      <VCol cols="12">
        <VCard>
          <!-- SECTION Header -->
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="11">
              <VBtn prepend-icon="tabler-arrow-narrow-left" :to="{ name: backRouteName }">
                Notifications
              </VBtn>
            </VCol>
            <VCol cols="1">
              <VBtn :to="{ name: 'notification-edit-id', params: { id: notification.id } }"
                :disabled="notification.status == 'validated'">
                Modifier
              </VBtn>
            </VCol>
            <VCol v-if="notification.status == 'rejected' && notification.status_observation">
              <VAlert color="warning">
                Motif du refus par {{ notification.creator.full_name }}: {{ notification.status_observation }}
              </VAlert>
            </VCol>
            <VCol v-if="notification.head_credit_validation == 'rejected' && notification.head_credit_observation">
              <VAlert color="warning">
                Motif du refus par head crédit: {{ notification.head_credit_observation }}
              </VAlert>
            </VCol>
            <VCol cols="12">
              <h2 class="text-center">
                Notification N°{{ notification.verbal_trial.committee_id }}
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
                Date de validation: {{ notification.verbal_trial.created_at_fr }}
              </p>
            </VCol>
            <VCol cols="6">
              <p style="font-size: 20px">
                : {{ notification.verbal_trial.caf.full_name }}
              </p>
              <p style="font-size: 20px">
                : <strong> {{ notification.verbal_trial.applicant_last_name + " " +
    notification.verbal_trial.applicant_first_name
                  }}</strong>
              </p>
              <p style="font-size: 20px">
                : {{ notification.verbal_trial.account_number }}
              </p>
              <p style="font-size: 20px">
                : {{ notification.verbal_trial.activity }}
              </p>
              <p style="font-size: 20px">
                : {{ notification.verbal_trial.purpose_of_financing }}
              </p>
              <p style="font-size: 20px">
                : {{ notification.verbal_trial.type_of_credit.name }}
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
              <h2>Informations de la notification</h2>
            </VCol>
            <VCol cols="12">
              <VTable class="text-no-wrap">
                <tbody>
                  <tr v-for="item in notificationData" :key="item.key">
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
                <li v-for="(item, index) in notification.guarantees" :key="index" style="font-size: 20px">
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
