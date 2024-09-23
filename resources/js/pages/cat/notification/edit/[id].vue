<!-- eslint-disable camelcase -->
<script setup>
definePage({
  meta: {
    action: 'update',
    subject: 'cat',
  },
})
import { ref } from 'vue'

const router = useRouter()
const route = useRoute("cat-edit-id")
const isSnackbarScrollReverseVisible = ref(false)
const snackbarMessage = ref("")
const refForm = ref()

const getResetCATError = () => {
  return {
    "notification_id": "",
    "credit_number": "",
    "sector": "",
    "first_deadline": "",
    "last_deadline": "",
    "source_of_reimbursement": "",
    "instructions_from_the_risk_and_credit_department": "",
    "outstanding_number_ready_to_settle": "",
    "other_expenses": "",
    "teg": "",
  }
}

const {
  data: notificationDataList,
} = await useApi(createUrl('/notification', {
  query: {
    paginate: 0,
    with_verbal_trial: 1,
    has_upload_completed: 1,
    has_cat: 0,
  },
}))


const {
  data: catData,
} = await useApi(createUrl(`/cat/${route.params.id}`, {
  query: {
    with_verbal_trial: 1
  },
}))


const onSubmit = () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const $data = {
        notification_id: cat.value.notification_id,
        credit_number: cat.value.credit_number,
        sector: cat.value.sector,
        first_deadline: cat.value.first_deadline,
        last_deadline: cat.value.last_deadline,
        source_of_reimbursement: cat.value.source_of_reimbursement,
        instructions_from_the_risk_and_credit_department: cat.value.instructions_from_the_risk_and_credit_department,
        outstanding_number_ready_to_settle: cat.value.outstanding_number_ready_to_settle,
        other_expenses: cat.value.other_expenses,
        teg: cat.value.teg,
      }

      const res = await $api(`cat/${route.params.id}`, {
        method: `PUT`,
        body: $data,
      })

      catError.value = getResetCATError()
      if (res.status == 200) {
        router.push({ name: "cat-notification" })
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
            catError.value[key] += message + "\n"
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
const catError = ref(getResetCATError())
const notificationList = computed(() => notificationDataList.value.data)
console.log(notificationDataList.value)
var cat = ref(catData.value.data.c_a_t)
notificationDataList.value.data.push(JSON.parse(JSON.stringify(cat.value.notification)))
</script>

<template>
  <div>
    <div class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6">
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">
          Mettre à jour un CAT
        </h4>
      </div>
    </div>
    <VForm ref="refForm" @submit.prevent="onSubmit">
      <VRow>
        <VCol cols="11">
          <VBtn prepend-icon="tabler-arrow-left" :to="{ name: 'cat-notification' }">
            CATs
          </VBtn>
        </VCol>
        <VCol cols="1">
          <VBtn prepend-icon='tabler-eye' :to="{ name: 'cat-notification-id', params: { id: route.params.id } }">
            Voir
          </VBtn>
        </VCol>
      </VRow>
      <VRow>
        <VCol md="12">
          <VCard class="mb-6" title="Information sur CAT">
            <VCardText>
              <VRow>
                <VCol cols="12" md="12" lg="12">
                  <AppAutocomplete v-model="cat.notification_id" :items="notificationList"
                    :error-messages="catError.notification_id" label="Notification"
                    placeholder="Ex: CFNTG-044-13-12-23-01212" :rules="[requiredValidator]"
                    item-title="verbal_trial.label" item-value="id" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="cat.credit_number" :error-messages="catError.credit_number"
                    label="Numéro du crédit" placeholder="Ex: 485632" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="cat.sector" :error-messages="catError.sector" label="Secteur"
                    placeholder="Ex: Agriculture" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppDateTimePicker v-model="cat.first_deadline" :error-messages="catError.first_deadline"
                    label="Date de première échéance" placeholder="Ex: 2024-02-02" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppDateTimePicker v-model="cat.last_deadline" :error-messages="catError.last_deadline"
                    label="Date de dernière échéance" placeholder="Ex: 2024-02-02" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppSelect v-model="cat.source_of_reimbursement" :items="[
      { value: 'revenue_from_the_activity', title: 'Recettes de l’activité' },
      { value: 'final_payer_settlement', title: 'Règlement du payeur final' },
      { value: 'resale_of_goods', title: 'Reventes des marchandises' }
    ]" :error-messages="catError.source_of_reimbursement" label="Source du remboursement"
                    placeholder="Ex: Recettes de l’activité" :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="cat.outstanding_number_ready_to_settle"
                    :error-messages="catError.outstanding_number_ready_to_settle" label="Numéro encours prêt à solder"
                    placeholder="Ex: " :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="cat.other_expenses" :error-messages="catError.other_expenses"
                    label="Autres frais" placeholder="Ex: " :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="6" lg="6">
                  <AppTextField v-model="cat.teg" :error-messages="catError.teg" label="TEG" placeholder="Ex: 15000600"
                    :rules="[requiredValidator]" />
                </VCol>
                <VCol cols="12" md="12" lg="12">
                  <AppTextarea v-model="cat.instructions_from_the_risk_and_credit_department"
                    :error-messages="catError.instructions_from_the_risk_and_credit_department"
                    label="Instructions du département risque et crédit" placeholder="Ex: " :rules="[requiredValidator]"
                    clearable counter />
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
