<!-- eslint-disable camelcase -->
<script setup>
import { createUrl } from "@/@core/composable/createUrl";
import { useApi } from "@/composables/useApi";
import GuaranteeEdit from "@/views/pv/GuaranteeEdit.vue";

definePage({
  meta: {
    action: "create",
    subject: "pv",
  },
});
import { ref } from "vue";

const router = useRouter();
const numPret = ref("");
const pretList = ref(null);

const clientsList = ref(null);
const numMatrucle = ref("");
const pvData = ref({
  committee_id: "",
  committee_date: "",
  caf_id: "",
  civility: "Mr",
  applicant_first_name: "",
  applicant_last_name: "",
  account_number: "",
  activity: "",
  fonction:"",
  purpose_of_financing: "",
  type_of_credit_id: "",
  amount: "",
  duration: "",
  periodicity: "mensual",
  due_amount: "",
  insurance_premium: "",
  frais_administration: "",
  administrative_fees_percentage: 0,
  // taf: 0,
  tax_fee_interest_rate: 14,
  credit_admin_id: "",
  guarantees: [
    {
      type_of_guarantee_id: 1,
      comment: "...",
    },
  ],
});

const recherClient = async () => {
  if (numMatrucle.value) {
    const response = await fetch(
      `/api/clients/comptes?search=${encodeURIComponent(numMatrucle.value)}`
    );
    if (!response.ok) {
      throw new Error("Erreur lors de la récupération des données");
    }
    return await response.json();
  }
};
watch(numMatrucle, async (newValue) => {
  if (newValue) {
    clientsList.value = await recherClient(newValue);
    const clientData = clientsList.value.data?.data?.[0];
    // console.log("dans le wacth", clientsList.value);
    pvData.value.applicant_first_name =
      clientData.nom_replegal ?? clientData.raison_sociale_client;
    pvData.value.applicant_last_name =
      clientData.nom_replegal ?? clientData.prenom_client;
    pvData.value.account_number = clientData.no_compte;
  } else {
    clientsList.value = [];
  }
});

const getResetPvError = () => {
  return {
    committee_id: "",
    committee_date: "",
    caf_id: "",
    civility: "",
    applicant_first_name: "",
    applicant_last_name: "",
    account_number: "",
    activity: "",
    fonction: "",
    purpose_of_financing: "",
    type_of_credit_id: "",
    amount: "",
    duration: "",
    periodicity: "",
    due_amount: "",
    insurance_premium: "",
    frais_administration: "",
    administrative_fees_percentage: "",
    // taf: "",
    tax_fee_interest_rate: "",
    credit_admin_id: "",
  };
};

const pvError = ref(getResetPvError());

const civilityItemList = [
  { value: "Mr", title: "Mr" },
  { value: "Mme", title: "Mme" },
  { value: "Mlle", title: "Mlle" },
];

const periodicityItemList = [
  { value: "mensual", title: "Mensuelle" },
  { value: "quarterly", title: "Trimestrielle" },
  { value: "semi-annual", title: "Semestrielle" },
  { value: "annual", title: "Annuelle" },
  { value: "in-fine", title: "A la fin" },
];

const { data: typeOfCreditListData } = await useApi(
  createUrl("/type-of-credit", {
    query: {
      paginate: 0,
    },
  })
);

const typeOfCreditList = computed(() => typeOfCreditListData.value.data);

const { data: cafListData } = await useApi(
  createUrl("/user", {
    query: {
      paginate: 0,
      profile: "caf",
    },
  })
);

const cafList = computed(() => cafListData.value.data);

const { data: creditAdminListData } = await useApi(
  createUrl("/user", {
    query: {
      paginate: 0,
      profile: "credit_admin",
    },
  })
);

const creditAdminList = computed(() => creditAdminListData.value.data);

const refForm = ref();

// Etat du dialogue de r\u00e9capitulatif et progression soumission
const showRecapDialog = ref(false);
const isSubmitting = ref(false);

// Helpers d'affichage
const getItemLabelById = (list, id, titleKey = "full_name") => {
  return list?.value?.find((i) => i.id == id)?.[titleKey] ?? id ?? "";
};

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      // Ouvre le r\u00e9capitulatif pour confirmation
      showRecapDialog.value = true;
    }
  });
};

const confirmSubmit = async () => {
  isSubmitting.value = true;
  pvError.value = getResetPvError();
  const res = await $api("/verbal-trial", {
    method: "POST",
    body: {
      committee_id: pvData.value.committee_id,
      committee_date: pvData.value.committee_date,
      caf_id: pvData.value.caf_id,
      civility: pvData.value.civility,
      applicant_first_name: pvData.value.applicant_first_name,
      applicant_last_name: pvData.value.applicant_last_name,
      account_number: pvData.value.account_number,
      activity: pvData.value.activity,
      fonction: pvData.value.fonction,
      purpose_of_financing: pvData.value.purpose_of_financing,
      type_of_credit_id: pvData.value.type_of_credit_id,
      amount: pvData.value.amount,
      duration: pvData.value.duration,
      periodicity: pvData.value.periodicity,
      due_amount: pvData.value.due_amount,
      insurance_premium: pvData.value.insurance_premium,
      frais_administration: pvData.value.frais_administration,
      administrative_fees_percentage: pvData.value.administrative_fees_percentage,
      taf: pvData.value.taf,
      tax_fee_interest_rate: pvData.value.tax_fee_interest_rate,
      guarantees: pvData.value.guarantees,
      credit_admin_id: pvData.value.credit_admin_id,
    },
  });

  let nextRoute = "/pv";
  if (res.status == 201) {
    pvData.value.guarantees.forEach((guarantee) => {
      if (guarantee.type_of_guarantee_id == 9) {
        nextRoute = "/pv/without-notification";
      }
    });
    showRecapDialog.value = false;
    router.push(nextRoute);
  } else if (res?.errors) {
    for (const key in res.errors) {
      res.errors[key].forEach((message) => {
        pvError.value[key] += message + "\n";
      });
    }
  }

  nextTick(() => {
    refForm.value?.resetValidation();
  });
  isSubmitting.value = false;
};

const removeGuaranteeItem = (id) => {
  pvData.value.guarantees.splice(id, 1);
};

const addGuaranteeItem = () => {
  pvData.value.guarantees.push({
    type_of_guarantee_id: 1,
    expiration_date: "",
    value: "",
    comment: "",
  });
};
const recherPret = async () => {
  if (numPret.value) {
    const response = await fetch(
      `/api/clients/prets?search=${encodeURIComponent(numPret.value)}`
    );
    if (!response.ok) {
      throw new Error("Erreur lors de la récupération des données");
    }
    return await response.json();
  }
};
watch(numPret, async (newValue) => {
  if (newValue) {
    pretList.value = await recherPret(newValue);
    console.log("dans le wacth", pretList.value);
    pvData.value.amount = pretList.value.data.data[0].mt_demande;
    pvData.value.due_amount = pretList.value.data.data[0].mt_pret_int;
    pvData.value.insurance_premium = pretList.value.data.data[0].mt_assurance;
    pvData.value.frais_administration = pretList.value.data.data[0].mt_fraidos;
    pvData.value.duration = pretList.value.data.data[0].nb_ech_pret;
    pvData.value.tax_fee_interest_rate =
      pretList.value.data.data[0].tx_int_pret;
    pvData.value.committee_id = pretList.value.data.data[0].ref_comite;
  } else {
    clientsList.value = [];
  }
});
</script>

<template>
  <div>
    <div
      class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-6"
    >
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">Ajouter un nouveau PV</h4>

        <span>Procès verbal pour un nouveau crédit</span>
      </div>
    </div>
    <VRow>
      <VCol cols="12" md="6" lg="4">
        <AppTextField v-model="numMatrucle" label="Numéro du matricule" />
      </VCol>
      <VCol cols="12" md="6" lg="4">
        <AppTextField v-model="numPret" label="Numéro du prêt" />
      </VCol>
    </VRow>
    <VForm ref="refForm" @submit.prevent="onSubmit">
      <VRow>
        <VCol md="12">
          <!-- 👉 PV Information -->
          <VCard class="mb-6" title="Information du pv">
            <VCardText>
              <VRow>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.committee_id"
                    :error-messages="pvError.committee_id"
                    label="Numéro du comitée"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppDateTimePicker
                    v-model="pvData.committee_date"
                    :error-messages="pvError.committee_date"
                    label="Date du comitée"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppAutocomplete
                    v-model="pvData.caf_id"
                    :items="cafList"
                    :error-messages="pvError.caf_id"
                    label="Chargé d'affaire"
                    item-title="full_name"
                    item-value="id"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppAutocomplete
                    v-model="pvData.credit_admin_id"
                    :items="creditAdminList"
                    :error-messages="pvError.credit_admin_id"
                    label="Administrateur Crédit"
                    item-title="full_name"
                    item-value="id"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <!-- Ca va certainement m'aider pour la suite -->
                <!-- <VCol cols="12" md="6" lg="4">
                  <AppAutocomplete
                    v-model="pvData.credit_admin_id"
                    :items="clients.user"
                    :error-messages="pvError.credit_admin_id"
                    label="Matricule client"
                    item-title="matricule_client"
                    item-value="id"
                    :rules="[requiredValidator]"
                  />
                </VCol> -->
                <VCol cols="12" md="6" lg="4">
                  <AppSelect
                    v-model="pvData.civility"
                    :items="civilityItemList"
                    :error-messages="pvError.civility"
                    label="Civilité"
                    placeholder="Ex: Mr"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.applicant_first_name"
                    :error-messages="pvError.applicant_first_name"
                    label="Prénom du demandeur"
                    placeholder="Ex: Cesar"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.applicant_last_name"
                    :error-messages="pvError.applicant_last_name"
                    label="Nom du demandeur"
                    placeholder="Ex: Endure"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.account_number"
                    :error-messages="pvError.account_number"
                    label="Numéro de compte"
                    placeholder="Ex: 251012345678"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.fonction"
                    :error-messages="pvError.fonction"
                    label="Profession"
                    placeholder="Ex: Homme d'affaire"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.activity"
                    :error-messages="pvError.activity"
                    label="Activé"
                    placeholder="Ex: vente de produits"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.purpose_of_financing"
                    :error-messages="pvError.purpose_of_financing"
                    label="Objet du financement"
                    placeholder="Ex: Achat nouveau locaux"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppAutocomplete
                    v-model="pvData.type_of_credit_id"
                    :items="typeOfCreditList"
                    :error-messages="pvError.type_of_credit_id"
                    label="Type de credit"
                    placeholder="Ex: Avance sur salaire"
                    item-title="full_name"
                    item-value="id"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.amount"
                    type="number"
                    :error-messages="pvError.amount"
                    label="Montant"
                    placeholder="Ex: 15 000 000"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.due_amount"
                    type="number"
                    :error-messages="pvError.due_amount"
                    label="Montant des interêts"
                    placeholder="Ex: 300 000"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.duration"
                    type="number"
                    :error-messages="pvError.duration"
                    label="Durée du crédit en mois"
                    placeholder="Ex: 18"
                    append-inner-icon="tabler-calendar"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppSelect
                    v-model="pvData.periodicity"
                    :items="periodicityItemList"
                    :error-messages="pvError.periodicity"
                    label="Periodicité"
                    placeholder="Ex: Mensuelle"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.insurance_premium"
                    type="number"
                    :error-messages="pvError.insurance_premium"
                    label="Prime d'assurance"
                    placeholder="Ex: 25000"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                <VCol cols="12" md="6" lg="4">
                  <AppTextField
                    v-model="pvData.frais_administration"
                    type="number"
                    :error-messages="pvError.frais_administration"
                    label="Frais d’administration crédit"
                    placeholder="Ex: 25000"
                    :rules="[requiredValidator]"
                  />
                </VCol>
                

                <!-- <VCol cols="12">
                  <VSlider
                    v-model="pvData.taf"
                    label="TAF(%)"
                    :error-messages="pvError.taf"
                    :thumb-size="15"
                    thumb-label="always"
                    :rules="[requiredValidator]"
                    step="0.1"
                  >
                    <template #append>
                      <VTextField
                        v-model="pvData.taf"
                        :error-messages="pvError.taf"
                        type="number"
                        style="width: 80px"
                        density="compact"
                        hide-details
                        variant="outlined"
                        suffix="%"
                      />
                    </template>
                  </VSlider>
                </VCol> -->
                <VCol cols="12">
                  <VSlider
                    v-model="pvData.administrative_fees_percentage"
                    label="Frais de dossier(%)"
                    :error-messages="pvError.administrative_fees_percentage"
                    :thumb-size="15"
                    thumb-label="always"
                    :rules="[requiredValidator]"
                    step="0.1"
                  >
                    <template #append>
                      <VTextField
                        v-model="pvData.administrative_fees_percentage"
                        :error-messages="pvError.administrative_fees_percentage"
                        type="number"
                        style="width: 80px"
                        density="compact"
                        hide-details
                        variant="outlined"
                        suffix="%"
                      />
                    </template>
                  </VSlider>
                </VCol>
                <VCol cols="12">
                  <VSlider
                    v-model="pvData.tax_fee_interest_rate"
                    label="Taux d'intérêt HT(%)"
                    :error-messages="pvError.tax_fee_interest_rate"
                    :thumb-size="15"
                    thumb-label="always"
                    :rules="[requiredValidator]"
                    step="0.1"
                  >
                    <template #append>
                      <VTextField
                        v-model="pvData.tax_fee_interest_rate"
                        :error-messages="pvError.tax_fee_interest_rate"
                        type="number"
                        style="width: 80px"
                        density="compact"
                        hide-details
                        variant="outlined"
                        suffix="%"
                      />
                    </template>
                  </VSlider>
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
          <VCard class="mb-6" title="Information des garanties">
            <VCardText class="add-products-form">
              <div
                v-for="(guarantee, index) in pvData.guarantees"
                :key="index"
                class="my-4 ma-sm-4"
              >
                <GuaranteeEdit
                  :id="index"
                  :data="guarantee"
                  @remove-guarantee="removeGuaranteeItem"
                />
              </div>

              <div class="mt-4 ma-sm-4">
                <VBtn prepend-icon="tabler-plus" @click="addGuaranteeItem">
                  Ajouter
                </VBtn>
              </div>
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

    <!-- Dialogue de r\u00e9capitulatif avant confirmation -->
    <VDialog v-model="showRecapDialog" max-width="900">
      <VCard>
        <VCardTitle class="text-h6">Récapitulatif du PV</VCardTitle>
        <VCardText>
          <VRow class="mb-2">
            <VCol cols="12" md="6">
              <strong>Numéro du comité:</strong>
              <div>{{ pvData.committee_id }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Date du comité:</strong>
              <div>{{ pvData.committee_date }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Chargé d'affaire:</strong>
              <div>{{ getItemLabelById(cafList, pvData.caf_id) }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Administrateur Crédit:</strong>
              <div>{{ getItemLabelById(creditAdminList, pvData.credit_admin_id) }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Civilité:</strong>
              <div>{{ pvData.civility }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Demandeur:</strong>
              <div>{{ pvData.applicant_first_name }} {{ pvData.applicant_last_name }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Numéro de compte:</strong>
              <div>{{ pvData.account_number }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Profession:</strong>
              <div>{{ pvData.fonction }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Activé:</strong>
              <div>{{ pvData.activity }}</div>
            </VCol> 
            <VCol cols="12" md="6">
              <strong>Objet du financement:</strong>
              <div>{{ pvData.purpose_of_financing }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Type de crédit:</strong>
              <div>{{ getItemLabelById(typeOfCreditList, pvData.type_of_credit_id, 'full_name') }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Montant demandé:</strong>
              <div>{{ pvData.amount }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Montant des intérêts:</strong>
              <div>{{ pvData.due_amount }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Durée (mois):</strong>
              <div>{{ pvData.duration }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Périodicité:</strong>
              <div>{{ pvData.periodicity }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Prime d'assurance:</strong>
              <div>{{ pvData.insurance_premium }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Frais d'administration:</strong>
              <div>{{ pvData.frais_administration }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Frais de dossier (%):</strong>
              <div>{{ pvData.administrative_fees_percentage }}</div>
            </VCol>
            <VCol cols="12" md="6">
              <strong>Taux d'intérêt HT (%):</strong>
              <div>{{ pvData.tax_fee_interest_rate }}</div>
            </VCol>
          </VRow>

          <VDivider class="my-4" />
          <h6 class="text-subtitle-1 mb-2">Garanties</h6>
          <VTable density="compact">
            <thead>
              <tr>
                <th>#</th>
                <th>Type</th>
                <th>Valeur</th>
                <th>Échéance</th>
                <th>Commentaire</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(g, idx) in pvData.guarantees" :key="idx">
                <td>{{ idx + 1 }}</td>
                <td>{{ g.type_of_guarantee_id }}</td>
                <td>{{ g.value ?? '-' }}</td>
                <td>{{ g.expiration_date ?? '-' }}</td>
                <td>{{ g.comment ?? '-' }}</td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
        <VCardActions class="justify-end">
          <VBtn variant="tonal" color="secondary" @click="showRecapDialog = false">
            Modifier
          </VBtn>
          <VBtn color="primary" :loading="isSubmitting" @click="confirmSubmit">
            Confirmer et enregistrer
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
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
