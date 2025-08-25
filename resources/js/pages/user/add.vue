<script setup>
import { $api } from "@/utils/api";
import axios from "axios";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";


definePage({
  meta: {
    // action: "read" || "historical",
    subject: "user",
  },
});

const pvData = ref({
  email: "",
  password: "",
  role: "",
  name: "",
});
const getResetPvError = () => {
  return {
    email: "",
    password: "",
    role: "",
    name: "",
  };
};
const roles = [
  { value: "admin", title: "Administrateur" },
  { value: "credit_analyst", title: "Analyst crédit" },
  { value: "head_credit", title: "Head credit" },
  { value: "credit_admin", title: "Admin crédit" },
  { value: "operation", title: "Opérations" },
  { value: "md", title: "MD" },
  { value: "caf", title: "CAF" },
  { value: "ca", title: "CA" },
  { value: "create_attestation", title: "Créer attestation" },
];

const router = useRouter();
const userToken = useCookie("userToken").value;
const $toast = useToast();

const refForm = ref();
const onSubmit = () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const { data } = await axios.post(
        "/api/user",
        {
          email: pvData.value.email,
          name: pvData.value.name,
          password: pvData.value.password,
          full_name: pvData.value.name,
          profile: pvData.value.role,
          activated: 1,
          password_change_required: 0,
        },
        {
          headers: {
            // "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${userToken}`,
          },
        }
      );

      if (data.status == 201) {
        let instance = $toast.success("Utilisateur ajouté!!", {
          position: "top-right",
        });
        router.push("/user");
      }
    }
  });
};
const pvError = ref(getResetPvError());
</script>
<template>
  <VCard class="mb-6">
    <VCardText>
      <VRow>
        <VCardText>
          <h2>Ajouter un utilisateur</h2>
        </VCardText>
      </VRow>
    </VCardText>
  </VCard>
  <VForm ref="refForm" @submit.prevent="onSubmit">
    <VRow>
      <VCol md="12">
        <!-- 👉 PV Information -->
        <VCard class="mb-6" title="Information sur l'utilisateur">
          <VCardText>
            <VRow>
              <VCol cols="12" md="6" lg="4">
                <AppTextField
                  v-model="pvData.name"
                  :error-messages="pvError.name"
                  label="Votre nom"
                  :rules="[requiredValidator]"
                />
              </VCol>
              <VCol cols="12" md="6" lg="4">
                <AppTextField
                  v-model="pvData.email"
                  :error-messages="pvError.email"
                  label="Votre e-mail"
                  :rules="[requiredValidator]"
                />
              </VCol>
              <VCol cols="12" md="6" lg="4">
                <AppSelect
                  v-model="pvData.role"
                  :items="roles"
                  :error-messages="pvError.role"
                  label="Rôle"
                  placeholder="Ex: Administrateur"
                  :rules="[requiredValidator]"
                />
              </VCol>
              <VCol cols="12" md="6" lg="4">
                <AppTextField
                  v-model="pvData.password"
                  :error-messages="pvError.password"
                  label="Mot de passe"
                  placeholder="Ex: Passer@1234"
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
</template>
