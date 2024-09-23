<!-- eslint-disable camelcase -->

<script setup>
import PledgeEdit from '@/views/contract/PledgeEdit.vue'
definePage({
  meta: {
    action: 'update',
    subject: 'contract',
  },
})
import { ref } from 'vue'

const router = useRouter()
const route = useRoute("contract-edit-id")
const isSnackbarScrollReverseVisible = ref(false)
const snackbarMessage = ref("")
const refForm = ref()
const typeList = [
  { value: "company", title: 'Société' },
  { value: "individual_business", title: 'Entreprise Individuel' },
  { value: "particular", title: 'Particulier' },
]
const hasPledgesLabel = {
  '1': 'Avec gage',
  '0': 'Sans gage',
}
const documentTypeList = [
  { value: "cni", title: 'Carte d\'identité nationale' },
  { value: "passport", title: 'Passeport' },
  { value: "residence_certificate", title: 'Certificat de résidence' },
  { value: "driving_licence", title: 'Permis de conduire' },
]

const getEmptyError = () => {
  return {
    "verbal_trial_id": "",
    "representative_birth_date": "",
    "representative_birth_place": "",
    "representative_nationality": "",
    "representative_home_address": "",
    "representative_phone_number": "",
    "representative_type_of_identity_document": "",
    "representative_number_of_identity_document": "",
    "representative_date_of_issue_of_identity_document": "",
    "risk_premium_percentage": "",
    "total_amount_of_interest": "",
    "number_of_due_dates": "",
    "type": "",
    "has_pledges": "",
    "company_denomination": "",
    "company_legal_status": "",
    "company_head_office_address": "",
    "company_rccm_number": "",
    "company_phone_number": "",
    "individual_business_denomination": "",
    "individual_business_corporate_purpose": "",
    "individual_business_head_office_address": "",
    "individual_business_rccm_number": "",
    "individual_business_phone_number": "",
  }
}

const {
  data: verbalTrialListData,
} = await useApi(createUrl('/verbal-trial', {
  query: {
    has_contract: 0,
    paginate: 0,
  },
}))

const {
  data: contractData,
} = await useApi(createUrl(`/contract/${route.params.id}`, {
  query: {
    with_verbal_trial: 1,
    with_company: 1,
    with_individual_business: 1,
    with_pledges: 1,
  },
}))

const onSubmit = () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const $data = {
        verbal_trial_id: contract.value.verbal_trial_id,
        representative_birth_date: contract.value.representative_birth_date,
        representative_birth_place: contract.value.representative_birth_place,
        representative_nationality: contract.value.representative_nationality,
        representative_home_address: contract.value.representative_home_address,
        representative_phone_number: contract.value.representative_phone_number,
        representative_type_of_identity_document: contract.value.representative_type_of_identity_document,
        representative_number_of_identity_document: contract.value.representative_number_of_identity_document,
        representative_date_of_issue_of_identity_document: contract.value.representative_date_of_issue_of_identity_document,
        risk_premium_percentage: contract.value.risk_premium_percentage,
        total_amount_of_interest: contract.value.total_amount_of_interest,
        number_of_due_dates: contract.value.number_of_due_dates,
        type: contract.value.type,
        has_pledges: contract.value.has_pledges,
      }

      if (contract.value.type == "company") {
        $data.company_denomination = contract.value.company.denomination
        $data.company_legal_status = contract.value.company.legal_status
        $data.company_head_office_address = contract.value.company.head_office_address
        $data.company_rccm_number = contract.value.company.rccm_number
        $data.company_phone_number = contract.value.company.phone_number
      } else if (contract.value.type == "individual_business") {
        $data.individual_business_denomination = contract.value.individual_business.denomination
        $data.individual_business_corporate_purpose = contract.value.individual_business.corporate_purpose
        $data.individual_business_head_office_address = contract.value.individual_business.head_office_address
        $data.individual_business_rccm_number = contract.value.individual_business.rccm_number
        $data.individual_business_phone_number = contract.value.individual_business.phone_number
      }

      if (contract.value.has_pledges == '1') {
        $data.pledges = contract.value.pledges
      }

      const res = await $api(`/contract/${route.params.id}`, {
        method: 'PUT',
        body: $data,
      })

      errorData.value = getEmptyError()
      if (res.status == 200) {
        router.push("/contract")
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
            errorData.value[key] += message + "\n"
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

const removePledgeItem = id => {
  contract.value.pledges.splice(id, 1)
}

const addPledgeItem = () => {
  // if (!contract.value.pledges) {
  //   contract.value.pledges = []
  // }
  contract.value.pledges.push({
    type: "vehicle",
    comment: "",
  })
}

const errorData = ref(getEmptyError())
const verbalTrialList = computed(() => verbalTrialListData.value.data)
var contract = ref(contractData.value.data.contract)
verbalTrialListData.value.data.push(JSON.parse(JSON.stringify(contract.value.verbal_trial)))
if (contract.value.company == null) {
  contract.value.company = {
    "denomination": "",
    "legal_status": "",
    "head_office_address": "",
    "rccm_number": "",
    "phone_number": "",
  }
}
if (contract.value.individual_business == null) {
  contract.value.individual_business = {
    "denomination": "",
    "corporate_purpose": "",
    "head_office_address": "",
    "rccm_number": "",
    "phone_number": "",
  }
}
</script>

<template>
  <div>
    <div class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6">
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">
          Modification de contrat
        </h4>
        <span>Dashboard/Contrats/Modification</span>
      </div>
    </div>
    <VForm ref="refForm" @submit.prevent="onSubmit">
      <VRow>
        <VCol cols="11">
          <VBtn :to="{ name: 'contract' }">
            <VIcon icon="tabler-arrow-left" />
            Contrats
          </VBtn>
        </VCol>
        <VCol cols="1">
          <VBtn append-icon="tabler-eye" :to="{ name: 'contract-id', params: { id: route.params.id } }">
            Voir
          </VBtn>
        </VCol>
      </VRow>
      <VRow>
        <VCol md="12">

          <!-- 👉 Informations sur le contrat -->
          <VCard class="mb-6" title="Information sur contrat">
            <VCardText>
              <VRow>
                <VCol>
                  <VAlert v-if="contract.status == 'rejected' && contract.status_observation" color="warning">
                    Motif du refus : {{ contract.status_observation }}
                  </VAlert>
                </VCol>
              </VRow>
              <VRow>
                <VCol cols="12" md="6" lg="6">
                  <AppAutocomplete v-model="contract.verbal_trial_id" :items="verbalTrialList"
                    :error-messages="errorData.verbal_trial_id" label="Procès verbal"
                    placeholder="Ex: CFNTG-044-13-12-23-01212" :rules="[requiredValidator]" item-title="label"
                    item-value="id" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.total_amount_of_interest" type="number"
                    :error-messages="errorData.total_amount_of_interest" label="Montant total des intérêts"
                    placeholder="Ex: 15 000 000" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.number_of_due_dates" type="number"
                    :error-messages="errorData.number_of_due_dates" label="Nombre d'échéance" placeholder="Ex: 18"
                    :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppSelect v-model="contract.type" :items="typeList" :error-messages="errorData.type" label="Type"
                    placeholder="Ex: Particulier" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="10">
                  <VSlider v-model="contract.risk_premium_percentage"
                    label="Prime de risque (en pourcentage) du demandeur"
                    :error-messages="errorData.risk_premium_percentage" :thumb-size="15" thumb-label="always"
                    :rules="[requiredValidator]" step="0.1">
                    <template #append>
                      <VTextField v-model="contract.risk_premium_percentage"
                        :error-messages="errorData.risk_premium_percentage" type="number" style="width:80px"
                        density="compact" hide-details variant="outlined" suffix="%" />
                    </template>
                  </VSlider>
                </VCol>
                <VCol cols="2">
                  <VCheckbox v-model="contract.has_pledges" :true-value="'1'" :false-value="'0'"
                    :label="hasPledgesLabel[contract.has_pledges]" :error-messages="errorData.has_pledges"
                    true-icon="tabler-check" false-icon="tabler-circle-x" color="success" />
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
          <!-- 👉 Information sur les gages -->
          <VCard v-if="contract.has_pledges == '1'" class=" mb-6" title="Informations sur les gages">
            <VCardText class="add-products-form">
              <div v-for="(pledge, index) in contract.pledges" class="my-4 ma-sm-4">
                <PledgeEdit :id="index" :data="pledge" @remove-pledge="removePledgeItem" />
              </div>

              <div class="mt-4 ma-sm-4">
                <VBtn prepend-icon="tabler-plus" @click="addPledgeItem">
                  Ajouter
                </VBtn>
              </div>
            </VCardText>
          </VCard>
          <!-- 👉 Informations sur le client -->
          <VCard class="mb-6" title="Information sur le client">
            <VCardText>
              <VRow>
                <VCol cols="12" md="6" lg="4">
                  <AppDateTimePicker v-model="contract.representative_birth_date"
                    :error-messages="errorData.representative_birth_date" label="Date de naissance"
                    placeholder="Ex: 2000-12-12" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="contract.representative_birth_place"
                    :error-messages="errorData.representative_birth_place" label="Lieu de naissance"
                    placeholder="Ex: Lomé" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="contract.representative_nationality"
                    :error-messages="errorData.representative_nationality" label="Nationalité"
                    placeholder="Ex: Togolaise" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="contract.representative_home_address"
                    :error-messages="errorData.representative_home_address" label="Addresse du domicile"
                    placeholder="Ex: Adewi, Lomé" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppSelect v-model="contract.representative_type_of_identity_document" :items="documentTypeList"
                    :error-messages="errorData.representative_type_of_identity_document"
                    label="Type de la pièce d'identité" placeholder="Ex: Passeport" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField v-model="contract.representative_number_of_identity_document"
                    :error-messages="errorData.representative_number_of_identity_document"
                    label="Numéro de la pièce d'identité" placeholder="Ex: 251012345678" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppDateTimePicker v-model="contract.representative_date_of_issue_of_identity_document"
                    :error-messages="errorData.representative_date_of_issue_of_identity_document"
                    label="Date de délivrance de la pièce d'identité" placeholder="Ex: 2022-01-01"
                    :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.representative_phone_number"
                    :error-messages="errorData.representative_phone_number" label="Numéro de téléphone"
                    placeholder="Ex: +228 96 96 96 96" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
          <!-- 👉 Information sur la société -->
          <VCard v-if="contract.type == 'company'" class="mb-6" title="Information sur la société">
            <VCardText>
              <VRow cols="12">
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.company.denomination" :error-messages="errorData.company_denomination"
                    label="Dénomination" placeholder="Ex: Adjovidjo" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.company.legal_status" :error-messages="errorData.company_legal_status"
                    label="Forme juridique" placeholder="Ex: " :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.company.rccm_number" :error-messages="errorData.company_rccm_number"
                    label="Numero RCCM" placeholder="Ex: RC-44E18" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.company.phone_number" :error-messages="errorData.company_phone_number"
                    label="Telephone de la société" placeholder="Ex: +228 96 96 96 96" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12">
                  <AppTextField v-model="contract.company.head_office_address"
                    :error-messages="errorData.company_head_office_address" label="Addresse du siège social"
                    placeholder="Ex: Lomé, Adewi" :rules="[requiredValidator]" />
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
          <!-- 👉 Information sur l'entreprise individuelle -->
          <VCard v-if="contract.type == 'individual_business'" class="mb-6"
            title="Information sur l'entreprise individuele">
            <VCardText>
              <VRow cols="12">
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.individual_business.denomination"
                    :error-messages="errorData.individual_business_denomination" label="Dénomination"
                    placeholder="Ex: Agban" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.individual_business.head_office_address"
                    :error-messages="errorData.individual_business_head_office_address" label="Addresse du siège social"
                    placeholder="Ex: Lomé, Adewi" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.individual_business.rccm_number"
                    :error-messages="errorData.individual_business_rccm_number" label="Numero RCCM"
                    placeholder="Ex: RC-44E18" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="contract.individual_business.phone_number"
                    :error-messages="errorData.individual_business_phone_number" label="Telephone de la société"
                    placeholder="Ex: +228 96 96 96 96" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12">
                  <AppTextField v-model="contract.individual_business.corporate_purpose"
                    :error-messages="errorData.individual_business_corporate_purpose" label="Objet social"
                    placeholder="Ex: " :rules="[requiredValidator]" />
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
