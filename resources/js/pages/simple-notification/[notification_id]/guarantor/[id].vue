<!-- eslint-disable camelcase -->
<script setup>
definePage({
  meta: {
    action: 'read',
    subject: 'guarantor',
  },
})
const router = useRouter()
const route = useRoute('notification-notification_id-guarantor-id')

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

const {
  data: guarantor,
} = await useApi(createUrl(`/guarantor/${route.params.id}`, {
  query: {
  },
}))


if (guarantor.value.status == 200) {
  guarantor.value = guarantor.value.data.guarantor
} else {
  router.push("/guarantor")
}

const tableData = [
  { "title": "Nom", "value": guarantor.value.last_name },
  { "title": "Date de naissance", "value": guarantor.value.birth_date },
  { "title": "Lieu de naissance", "value": guarantor.value.birth_place },
  { "title": "Nationalité", "value": guarantor.value.nationality },
  { "title": "Addresse du domicile", "value": guarantor.value.home_address },
  { "title": "Type de la pièce d'identité", "value": documentTypeList[guarantor.value.type_of_identity_document] },
  { "title": "Numéro de la pièce d'identité", "value": guarantor.value.number_of_identity_document },
  { "title": "Date de délivrance de la pièce d'identité", "value": guarantor.value.date_of_issue_of_identity_document },
  { "title": "Fonction", "value": guarantor.value.function },
  { "title": "Numéro de téléphone", "value": guarantor.value.phone_number },
]
</script>

<template>
  <section v-if="guarantor">
    <VRow>
      <VCol cols="12">
        <VCard>
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row text-lg">
            <VCol cols="11">
              <VBtn
                :to="{ name: 'notification-notification_id-guarantor', params: { notification_id: route.params.notification_id } }">
                <VIcon icon="tabler-arrow-left" />
                Cautions
              </VBtn>
            </VCol>
            <VCol cols="1">
              <VBtn
                :to="{ name: 'notification-notification_id-guarantor-edit-id', params: { notification_id: route.params.notification_id, id: guarantor.id } }">
                Modifier
              </VBtn>
            </VCol>
            <VCol cols="12">
              <h2 class="text-center">
                Caution N°{{ guarantor.id }}
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
