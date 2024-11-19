<!-- eslint-disable vue/no-mutating-props -->
<script setup>
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
    }),
  },
});

const emit = defineEmits(["removePledge"]);

const typeOfPledgeList = [
  { value: "vehicle", name: "Véhicule" },
  { value: "stock", name: "Stock" },
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
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppTextField
            v-model="localPledgeData.montant_estime"
            label="Montant estimé"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppTextField
            v-model="localPledgeData.genre"
            label="Genre"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppTextField
            v-model="localPledgeData.marque"
            label="Marque"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppTextField
            v-model="localPledgeData.model"
            label="Model"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppTextField
            v-model="localPledgeData.numero_serie"
            label="Numéro de série/châssis"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppTextField
            v-model="localPledgeData.immatriculation"
            label="Immatriculation"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppDateTimePicker
            v-model="localPledgeData.date_carte_crise"
            label="Date d'établissement carte grise"
            :rules="[requiredValidator]"
          />
        </VCol>
        <VCol v-if="localPledgeData.type == 'vehicle'" cols="12" md="6">
          <AppDateTimePicker
            v-model="localPledgeData.date_mise_en_circulation"
            label="Date mise en circulation"
            :rules="[requiredValidator]"
          />
        </VCol>
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
