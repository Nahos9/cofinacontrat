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
