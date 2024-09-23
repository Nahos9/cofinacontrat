<template>
  <VAuto
    v-model="selectedClient"
    :items="clients"
    :loading="loading"
    :search-input.sync="search"
    item-title="INTITULE_COMPTE"
    item-value="MATRICULE_CLIENT"
    placeholder="Rechercher un client"
    @update:search="searchClients"
    @update:model-value="fillClientInfo"
  >
    <template v-slot:item="{ props, item }">
      <v-list-item v-bind="props">
        <v-list-item-title>{{ item.INTITULE_COMPTE }}</v-list-item-title>
        <v-list-item-subtitle>{{ item.NO_COMPTE }}</v-list-item-subtitle>
      </v-list-item>
    </template>
  </VAuto>
</template>

<script setup>
import { ref, watch } from "vue";
import { useApi } from "@/composables/useApi";

const selectedClient = ref(null);
const clients = ref([]);
const loading = ref(false);
const search = ref("");

const searchClients = async (val) => {
  if (val && val.length > 2) {
    loading.value = true;
    try {
      const { data } = await useApi(
        createUrl("/api/clients/search", {
          query: {
            search: val,
            limit: 20,
          },
        })
      );
      clients.value = data.value?.data || [];
    } catch (error) {
      console.error("Erreur lors de la recherche des clients:", error);
    } finally {
      loading.value = false;
    }
  } else {
    clients.value = [];
  }
};

const fillClientInfo = (client) => {
  if (client) {
    // Remplir les informations du client sélectionné
    console.log("Client sélectionné:", client);
    // Mettez à jour vos champs de formulaire ici
  }
};

// Optionnel : déclencher la recherche lorsque la valeur de recherche change
watch(search, (newVal) => {
  if (newVal && newVal.length > 2) {
    searchClients(newVal);
  }
});
</script>
