<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toast-notification';
import { VInput } from 'vuetify/lib/components/index.mjs';


definePage({
  meta: {
    action: "create",
    subject: "attestation",
  },
});

const form = ref({
  last_name: "",
  first_name: "",
  email: "",
  phone: "",
  account_number: "",
  date_de_creation_compte: "",
  montant_endettement: "",
  type_attestation: "",
  type: "",
  gages: [], // Nouveau champ pour les gages
});
const $toast = useToast();
const router = useRouter();
const types  = [
  "personne physique",
  "personne morale",
]
const type_attestation = [
  "main levée de gage",
  "cloture",
  "endettement",
  "non endettement",
]
const civilites = [
  "Monsieur",
  "Madame",
]
const userToken = useCookie("userToken");

// Fonction pour ajouter un nouveau gage
const addGage = () => {
  form.value.gages.push({
    marque: "",
    immatriculation: ""
  });
};

// Fonction pour supprimer un gage
const removeGage = (index) => {
  form.value.gages.splice(index, 1);
};

// Fonction pour initialiser le premier gage si nécessaire
const initializeGages = () => {
  if (form.value.type_attestation === "main levée de gage" && form.value.gages.length === 0) {
    addGage();
  }
};

// Surveiller les changements du type d'attestation
watch(() => form.value.type_attestation, (newValue) => {
  if (newValue === "main levée de gage") {
    if (form.value.gages.length === 0) {
      addGage();
    }
  } else {
    form.value.gages = [];
  }
});

const createAttestation = async () => {
  try {
    await axios.post('/api/attestation', {
    last_name: form.value.last_name,
    first_name: form.value.first_name,
    civilite: form.value.civilite,
    raison_sociale: form.value.raison_sociale,
    email: form.value.email,
    phone: form.value.phone,
    account_number: form.value.account_number,
    date_de_creation_compte: form.value.date_de_creation_compte,
    type: form.value.type,
    type_attestation: form.value.type_attestation,
    montant_endettement: form.value.montant_endettement,
    gages: form.value.gages, // Ajouter les gages à la requête
  }, {
    headers: {
      'Authorization': `Bearer ${userToken.value}`,
    },
  })
    .then(res => {
      if (res.status == 200) {
      let instance = $toast.success("Attestation créée!!", {
        position: "top-right",
      });
      router.push('/attestation');

      form.value = {
        last_name: "",
        first_name: "",
        civilite: "",
        raison_sociale: "",
        email: "",
        phone: "",
        account_number: "",
        date_de_creation_compte: "",
        type: "",
        type_attestation: "", 
        montant_endettement: "",
        gages: [], // Réinitialiser les gages
      }
    }
  })
  } catch (err) {
    console.log(err);
  }
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardTitle>Nouvelle attestation</VCardTitle>
        <VCardText>
          <VForm @submit.prevent="createAttestation">
            <VSelect class="z-index-1000 mb-2" label="Type" v-model="form.type" :items="types" />
            <VSelect class="z-index-40 mb-2" label="Type d'attestation" v-model="form.type_attestation" :items="type_attestation" />
            <VSelect class="z-index-40 mb-2" v-if="form.type == 'personne physique'" label="Civilité" v-model="form.civilite" :items="civilites"  />
            <div class="d-flex  gap-2 mb-2" v-if="form.type == 'personne physique'">
              <VTextField label="Nom" v-model="form.last_name" />
              <VTextField label="Prénom" v-model="form.first_name" />
            </div>
            <div v-if="form.type == 'personne morale'" class="mb-2">
              <VTextField label="Raison sociale" v-model="form.raison_sociale" />
            </div>
            <div class="d-flex gap-2 mb-2">
              <VTextField label="Email" v-model="form.email" />
              <VTextField label="Téléphone" v-model="form.phone" />
            </div>
            <VTextField label="Numéro de compte" class="mb-2" v-model="form.account_number" />
            <VTextField label="Montant d'endettement" v-if="form.type_attestation == 'endettement'" class="mb-2" v-model="form.montant_endettement" />
            
            <!-- Section des gages pour "main levée de gage" -->
            <div v-if="form.type_attestation === 'main levée de gage'" class="mb-4">
              <VCard variant="outlined" class="pa-4">
                <VCardTitle class="text-h6 mb-3">Gages</VCardTitle>
                <div v-for="(gage, index) in form.gages" :key="index" class="d-flex gap-2 mb-3 align-center">
                  <VTextField 
                    label="Marque" 
                    v-model="gage.marque" 
                    class="flex-grow-1"
                    placeholder="Ex: Toyota, BMW, etc."
                  />
                  <VTextField 
                    label="Immatriculation" 
                    v-model="gage.immatriculation" 
                    class="flex-grow-1"
                    placeholder="Ex: 1234ABC"
                  />
                  <VBtn 
                    icon 
                    color="error" 
                    variant="outlined" 
                    @click="removeGage(index)"
                    :disabled="form.gages.length === 1"
                    class="ml-2"
                  >
                    <VIcon>mdi-delete</VIcon>
                  </VBtn>
                </div>
                <VBtn 
                  color="primary" 
                  variant="outlined" 
                  @click="addGage"
                  class="mt-2"
                >
                  <VIcon class="mr-2">mdi-plus</VIcon>
                  Ajouter un gage
                </VBtn>
              </VCard>
            </div>
            
            <VTextField type="date" class="mb-2"  label="Date de création du compte" v-model="form.date_de_creation_compte" />
           <div class="d-flex gap-2">
            <VBtn type="submit" class="z-index-1000 mb-2">Créer</VBtn>
            <VBtn type="reset" class="z-index-1000 mb-2">Réinitialiser</VBtn>
           </div>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
