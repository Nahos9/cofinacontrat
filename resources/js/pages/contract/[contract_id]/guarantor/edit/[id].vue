<!-- eslint-disable camelcase -->
<script setup>
definePage({
  meta: {
    action: "update",
    subject: "guarantor",
  },
});
import { createUrl } from "@/@core/composable/createUrl";
import { useApi } from "@/composables/useApi";
import { ref } from "vue";

const router = useRouter();
const route = useRoute("contract-contract_id-guarantor-edit-id");

const { data: guarantorAPI } = await useApi(
  createUrl(`/guarantor/${route.params.id}`, {
    query: {
      with_contract: 1,
    },
  })
);

const guarantorItem = computed(() => guarantorAPI.value.data.guarantor);

const getResetGuarantorError = () => {
  return {
    civility: "",
    first_name: "",
    last_name: "",
    birth_date: "",
    birth_place: "",
    nationality: "",
    home_address: "",
    type_of_identity_document: "",
    number_of_identity_document: "",
    date_of_issue_of_identity_document: "",
    function: "",
    phone_number: "",
  };
};

const guarantorError = ref(getResetGuarantorError());

const civilityItemList = [
  { value: "Mr", title: "Mr" },
  { value: "Mme", title: "Mme" },
  { value: "Mlle", title: "Mlle" },
];

const documentTypeList = [
  { value: "cni", title: "Carte d'identité nationale" },
  { value: "passport", title: "Passeport" },
  { value: "residence_certificate", title: "Certificat de résidence" },
  { value: "driving_licence", title: "Permis de conduire" },
];

const refForm = ref();

const onSubmit = () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const res = await $api(`/guarantor/${route.params.id}`, {
        method: "PUT",
        body: {
          contract_id: route.params.contract_id,
          civility: guarantorItem.value.civility,
          first_name: guarantorItem.value.first_name,
          last_name: guarantorItem.value.last_name,
          birth_date: guarantorItem.value.birth_date,
          birth_place: guarantorItem.value.birth_place,
          nationality: guarantorItem.value.nationality,
          home_address: guarantorItem.value.home_address,
          type_of_identity_document:
            guarantorItem.value.type_of_identity_document,
          number_of_identity_document:
            guarantorItem.value.number_of_identity_document,
          date_of_issue_of_identity_document:
            guarantorItem.value.date_of_issue_of_identity_document,
          function: guarantorItem.value.function,
          phone_number: guarantorItem.value.phone_number,
        },
      });

      guarantorError.value = getResetGuarantorError();
      if (res.status == 200) {
        router.push({
          name: "contract-contract_id-guarantor",
          params: { contract_id: route.params.contract_id },
        });
      } else {
        for (const key in res.errors) {
          res.errors[key].forEach((message) => {
            guarantorError.value[key] += message + "\n";
          });
        }
      }
      nextTick(() => {
        // refForm.value?.reset()
        refForm.value?.resetValidation();
      });
    }
  });
};
</script>

<template>
  <div>
    <div
      class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"
    >
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">Modifer une caution</h4>
      </div>
    </div>
    <VForm ref="refForm" @submit.prevent="onSubmit">
      <VRow>
        <VCol cols="11">
          <VBtn
            prepend-icon="tabler-arrow-left"
            :to="{
              name: 'contract-contract_id-guarantor',
              params: { contract_id: route.params.contract_id },
            }"
          >
            Cautions
          </VBtn>
        </VCol>
        <VCol cols="1">
          <VBtn
            prepend-icon="tabler-eye"
            :to="{
              name: 'contract-contract_id-guarantor-id',
              params: {
                contract_id: route.params.contract_id,
                id: route.params.id,
              },
            }"
          >
            Voir
          </VBtn>
        </VCol>
      </VRow>
      <VRow>
        <VCol md="12">
          <VCard class="mb-6" title="Information de la caution ">
            <VCardText>
              <VRow>
                <VCol cols="12" md="6" lg="4">
                  <AppSelect
                    v-model="guarantorItem.civility"
                    :items="civilityItemList"
                    :error-messages="guarantorError.civility"
                    label="Civilité"
                    placeholder="Ex: Mr"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.first_name"
                    :error-messages="guarantorError.first_name"
                    label="Prénom"
                    placeholder="Ex: Cesar"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.last_name"
                    :error-messages="guarantorError.last_name"
                    label="Nom"
                    placeholder="Ex: DEFALGO"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppDateTimePicker
                    v-model="guarantorItem.birth_date"
                    :error-messages="guarantorError.birth_date"
                    label="Date de naissance"
                    placeholder="Ex: 2000-12-12"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.birth_place"
                    :error-messages="guarantorError.birth_place"
                    label="Lieu de naissance"
                    placeholder="Ex: Lomé"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.nationality"
                    :error-messages="guarantorError.nationality"
                    label="Nationalité"
                    placeholder="Ex: Togolaise"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.home_address"
                    :error-messages="guarantorError.home_address"
                    label="Addresse du domicile"
                    placeholder="Ex: Adewi, Lomé"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppSelect
                    v-model="guarantorItem.type_of_identity_document"
                    :items="documentTypeList"
                    :error-messages="guarantorError.type_of_identity_document"
                    label="Type de la pièce d'identité"
                    placeholder="Ex: Passeport"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.number_of_identity_document"
                    :error-messages="guarantorError.number_of_identity_document"
                    label="Numéro de la pièce d'identité"
                    placeholder="Ex: 251012345678"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppDateTimePicker
                    v-model="guarantorItem.date_of_issue_of_identity_document"
                    :error-messages="
                      guarantorError.date_of_issue_of_identity_document
                    "
                    label="Date de délivrance de la pièce d'identité"
                    placeholder="Ex: 2022-01-01"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.function"
                    :error-messages="guarantorError.function"
                    label="Fonction"
                    placeholder="Ex: Agent de change"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="guarantorItem.phone_number"
                    :error-messages="guarantorError.phone_number"
                    label="Numéro de téléphone"
                    placeholder="Ex: +228 96 96 96 96"
                    :rules="[requiredValidator]"
                  />
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
        </VCol>
        <VCol cols="12">
          <div
            class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"
          >
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
        color: #fff !important;
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
