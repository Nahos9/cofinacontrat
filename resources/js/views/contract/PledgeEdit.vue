<!-- eslint-disable vue/no-mutating-props -->
<script setup>
import { VRow } from "vuetify/lib/components/index.mjs";

const props = defineProps({
  id: {
    type: Number,
    required: true,
  },
  data: {
    type: Object,
    required: true,
    default: () => ({
      type: "vehicule",
      montant_estime: "",
      marque: "",
      date_mise_en_circulation: "",
      date_carte_crise: "",
      immatriculation: "",
      numero_serie: "",
      genre: "",
      model: "",
      civility: "",
      nom: "",
      prenom: "",
      date_naiss: "",
      lieux_naiss: "",
      identity_document: "",
      num_identity_document: "",
      office_delivery: "",
      phone: "",
      adresse: "",
      nationalite: "",
      date_delivery_document: "",
    }),
  },
});

const emit = defineEmits(["removePledge"]);

const typeOfPledgeList = [
  { value: "vehicle", name: "Véhicule" },
  { value: "stock", name: "Stock" },
];
const documentTypeList = [
  { value: "cni", title: "Carte d'identité nationale" },
  { value: "passport", title: "Passeport" },
  { value: "residence_certificate", title: "Certificat de résidence" },
  { value: "driving_licence", title: "Permis de conduire" },
  { value: "carte_sej", title: "Carte de séjour" },
  { value: "recep", title: "Récépissé de la carte nationale d’identité " },
];
const civilityItemList = [
  { value: "Mr", title: "Mr" },
  { value: "Mme", title: "Mme" },
  { value: "Mlle", title: "Mlle" },
];
const localPledgeData = ref(props.data);

const removePledge = () => {
  emit("removePledge", props.id);
};
</script>

<template>
  <VCard flat border class="d-flex flex-row">
    <!-- 👉 Left Form -->
    <div class="pa-5 flex-grow-1">
      <VRow>
        <VCol cols="12">
          <AppSelect
            v-model="localPledgeData.type"
            :items="typeOfPledgeList"
            item-title="name"
            item-value="value"
            label="Type de Gage"
            placeholder="Choisir le type de gage"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VRow v-if="localPledgeData.type == 'vehicle'">
          <VCol cols="12" md="6">
            <AppTextField
              v-model="localPledgeData.montant_estime"
              label="Montant estimé"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="localPledgeData.genre"
              label="Genre"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="localPledgeData.marque"
              label="Marque"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="localPledgeData.model"
              label="Model"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="localPledgeData.numero_serie"
              label="Numéro de série/châssis"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="localPledgeData.immatriculation"
              label="Immatriculation"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppDateTimePicker
              v-model="localPledgeData.date_carte_crise"
              label="Date d'établissement carte grise"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppDateTimePicker
              v-model="localPledgeData.date_mise_en_circulation"
              label="Date mise en circulation"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="6" lg="4">
            <AppSelect
              v-model="localPledgeData.civility"
              :items="civilityItemList"
              label="Civilité"
              placeholder="Ex: Mr"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.nom"
              label="Nom"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.prenom"
              label="Prénom"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppDateTimePicker
              v-model="localPledgeData.date_naiss"
              label="Date de naissance"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.lieux_naiss"
              label="Lieux de naissance"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppSelect
              v-model="localPledgeData.identity_document"
              :items="documentTypeList"
              label="Type de la pièce d'identité"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.num_identity_document"
              label="Numéro de la pièce"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppDateTimePicker
              v-model="localPledgeData.date_delivery_document"
              label="Date de délivrance"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.office_delivery"
              label="Delivrée par"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.adresse"
              label="Adresse"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.nationalite"
              label="Nationalité"
              :rules="[requiredValidator]"
            />
          </VCol>
          <VCol cols="12" md="4">
            <AppTextField
              v-model="localPledgeData.phone"
              label="Numéro de téléhone"
              :rules="[requiredValidator]"
            />
          </VCol>
        </VRow>
      </VRow>
    </div>

    <!-- 👉 Item Actions -->
    <div class="d-flex flex-column justify-space-between border-s pa-1">
      <IconBtn @click="removePledge">
        <VIcon size="20" icon="tabler-x" />
      </IconBtn>
    </div>
  </VCard>
</template>
