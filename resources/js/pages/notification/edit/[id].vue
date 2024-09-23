<!-- eslint-disable camelcase -->

<script setup>
definePage({
  meta: {
    action: 'update',
    subject: 'notification',
  },
})
import { ref } from 'vue'

const router = useRouter()
const route = useRoute("notification-edit-id")
const isSnackbarScrollReverseVisible = ref(false)
const snackbarMessage = ref("")
const refForm = ref()
const typeList = [
  { value: "company", title: 'Société' },
  { value: "individual_business", title: 'Entreprise Individuel' },
  { value: "particular", title: 'Particulier' },
]
const documentTypeList = [
  { value: "cni", title: 'Carte d\'identité nationale' },
  { value: "passport", title: 'Passeport' },
  { value: "residence_certificate", title: 'Certificat de résidence' },
  { value: "driving_licence", title: 'Permis de conduire' },
]

const getResetFormError = () => {
  return {
    verbal_trial_id: "",
    representative_phone_number: "",
    representative_home_address: "",
    number_of_due_dates: "",
    risk_premium_percentage: "",
    total_amount_of_interest: "",
    representative_type_of_identity_document: "",
    representative_number_of_identity_document: "",
    representative_date_of_issue_of_identity_document: "",
    type: "",
    business_denomination: "",
  }
}

const {
  data: verbalTrialListData,
} = await useApi(createUrl('/verbal-trial', {
  query: {
    has_notification: 0,
    paginate: 0,
    has_mortgage: 1,
    status: 'v',
  },
}))

const {
  data: notificationData,
} = await useApi(createUrl(`/notification/${route.params.id}`, {
  query: {
    with_verbal_trial: 1,
  },
}))

const onSubmit = () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const $data = {
        verbal_trial_id: notification.value.verbal_trial_id,
        representative_phone_number: notification.value.representative_phone_number,
        representative_home_address: notification.value.representative_home_address,
        number_of_due_dates: notification.value.number_of_due_dates,
        risk_premium_percentage: notification.value.risk_premium_percentage,
        total_amount_of_interest: notification.value.total_amount_of_interest,
        representative_type_of_identity_document: notification.value.representative_type_of_identity_document,
        representative_number_of_identity_document: notification.value.representative_number_of_identity_document,
        representative_date_of_issue_of_identity_document: notification.value.representative_date_of_issue_of_identity_document,
        type: notification.value.type,
        business_denomination: notification.value.business_denomination,
      }

      const res = await $api(`/notification/${route.params.id}`, {
        method: 'PUT',
        body: $data,
      })

      formError.value = getResetFormError()
      if (res.status == 200) {
        router.push("/notification")
      } else if (res.status == 403) {
        isSnackbarScrollReverseVisible.value = true
        snackbarMessage.value = ""
        for (const key in res.errors) {
          res.errors[key].forEach(message => {
            snackbarMessage.value += message + "\n";
          })
        }
      } else {
        for (const key in res.errors) {
          res.errors[key].forEach(message => {
            formError.value[key] += message + "\n"
          })
        }
      }
      nextTick(() => {
        // refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}

const formError = ref(getResetFormError())
const verbalTrialList = computed(() => verbalTrialListData.value.data)
var notification = ref(notificationData.value.data.notification)
verbalTrialListData.value.data.push(JSON.parse(JSON.stringify(notification.value.verbal_trial)))
</script>

<template>
  <div>
    <div class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6">
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">
          Modification de notification
        </h4>
        <span>Dashboard/Notifications/Modification</span>
      </div>
    </div>
    <VForm ref="refForm" @submit.prevent="onSubmit">
      <VRow>
        <VCol cols="11">
          <VBtn :to="{ name: 'notification' }">
            <VIcon icon="tabler-arrow-left" />
            Notifications
          </VBtn>
        </VCol>
        <VCol cols="1">
          <VBtn :to="{ name: 'notification-id', params: { id: route.params.id } }">
            Voir
          </VBtn>
        </VCol>
      </VRow>
      <VRow>
        <VCol md="12">

          <!-- 👉 Informations sur le notification -->
          <VCard class="mb-6" title="Information sur notification">
            <VCardText>
              <VRow>
                <VCol cols="12" md="6" lg="4">
                  <AppAutocomplete v-model="notification.verbal_trial_id" :items="verbalTrialList"
                    :error-messages="formError.verbal_trial_id" label="Procès verbal"
                    placeholder="Ex: CFNTG-044-13-12-23-01212" :rules="[requiredValidator]" item-title="label"
                    item-value="id" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="notification.representative_phone_number"
                    :error-messages="formError.representative_phone_number" label="Numéro de téléphone"
                    placeholder="Ex: +228 96 96 96 96" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="notification.representative_home_address"
                    :error-messages="formError.representative_home_address" label="Addresse" placeholder="Ex: Adewi"
                    :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField type="number" v-model="notification.number_of_due_dates"
                    :error-messages="formError.number_of_due_dates" label="Nombre d'échéance" placeholder="Ex: 4"
                    :rules="[requiredValidator]" />
                </VCol>

                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="notification.total_amount_of_interest" type="number"
                    :error-messages="formError.total_amount_of_interest" label="Montant total des intérêts"
                    placeholder="Ex: 15 000 000" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppSelect v-model="notification.type" :items="typeList" :error-messages="formError.type" label="Type"
                    placeholder="Ex: Particulier" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppSelect v-model="notification.representative_type_of_identity_document" :items="documentTypeList"
                    :error-messages="formError.representative_type_of_identity_document"
                    label="Type de la pièce d'identité" placeholder="Ex: Passeport" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="notification.representative_number_of_identity_document"
                    :error-messages="formError.representative_number_of_identity_document"
                    label="Numéro de la pièce d'identité" placeholder="Ex: 251012345678" :rules="[requiredValidator]" />
                </VCol>

                <VCol cols="12" md="12" lg="4">
                  <AppDateTimePicker v-model="notification.representative_date_of_issue_of_identity_document"
                    :error-messages="formError.representative_date_of_issue_of_identity_document"
                    label="Date de délivrance de la pièce d'identité" placeholder="Ex: 2022-01-01"
                    :rules="[requiredValidator]" />
                </VCol>

                <VCol cols="12">
                  <VSlider v-model="notification.risk_premium_percentage"
                    label="Prime de risque (en pourcentage) du demandeur"
                    :error-messages="formError.risk_premium_percentage" :thumb-size="15" thumb-label="always"
                    :rules="[requiredValidator]" step="0.1">
                    <template #append>
                      <VTextField v-model="notification.risk_premium_percentage"
                        :error-messages="formError.risk_premium_percentage" type="number" style="width:80px"
                        density="compact" hide-details variant="outlined" suffix="%" />
                    </template>
                  </VSlider>
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
          <!-- 👉 Information sur la société -->
          <VCard v-if="notification.type == 'company'" class="mb-6" title="Information sur la société">
            <VCardText>
              <VRow cols="12">
                <VCol cols="12">
                  <AppTextField v-model="notification.business_denomination"
                    :error-messages="formError.business_denomination" label="Dénomination" placeholder="Ex: Adjovidjo"
                    :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
          <!-- 👉 Information sur l'entreprise individuelle -->
          <VCard v-if="notification.type == 'individual_business'" class="mb-6"
            title="Information sur l'entreprise individuele">
            <VCardText>
              <VRow cols="12">
                <VCol cols="12">
                  <AppTextField v-model="notification.business_denomination"
                    :error-messages="formError.business_denomination" label="Dénomination" placeholder="Ex: Agban"
                    :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
        </VCol>

        <VCol cols="12">
          <div class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6">
            <div class="d-flex flex-column justify-center" />
            <div class="d-flex gap-4 align-center flex-wrap">
              <VBtn type="reset" variant="tonal" color="primary">
                <VIcon start icon="tabler-circle-minus" />
                Effacer
              </VBtn>
              <VBtn type="submit" class="me-3">
                Enregistrer
                <VIcon end icon="tabler-checkbox" />
              </VBtn>
            </div>
          </div>
        </VCol>
      </VRow>
    </VForm>

    <VSnackbar v-model="isSnackbarScrollReverseVisible" transition="scroll-y-reverse-transition" location="bottom end"
      color="error">
      {{ snackbarMessage }}
    </VSnackbar>
  </div>
</template>

<style lang="scss" scoped>
.drop-zone {
  border: 2px dashed rgba(var(--v-theme-on-surface), 0.12);
  border-radius: 6px;
}
</style>

<style lang="scss">
.inventory-card {

  .v-radio-group,
  .v-checkbox {
    .v-selection-control {
      align-items: start !important;

      .v-selection-control__wrapper {
        margin-block-start: -0.375rem !important;
      }
    }

    .v-label.custom-input {
      border: none !important;
    }
  }

  .v-tabs.v-tabs-pill {
    .v-slide-group-item--active.v-tab--selected.text-primary {
      h6 {
        color: #fff !important
      }
    }
  }

}

.ProseMirror {
  p {
    margin-block-end: 0;
  }

  padding: 0.5rem;
  outline: none;

  p.is-editor-empty:first-child::before {
    block-size: 0;
    color: #adb5bd;
    content: attr(data-placeholder);
    float: inline-start;
    pointer-events: none;
  }
}
</style>
