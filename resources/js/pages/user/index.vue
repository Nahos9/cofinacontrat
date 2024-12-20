<script setup>
import { paginationMeta } from "@/plugins/fake-api/utils/paginationMeta";
import axios from "axios";
import { computed, onMounted, ref, watch } from "vue";
import { VDataTableServer } from "vuetify/lib/labs/components.mjs";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
definePage({
  meta: {
    // action: "read" || "historical",
    subject: "pv",
  },
});

// const users = ref([]);
const users = [];
const userToken = useCookie("userToken").value;
const page = ref(1);
const itemsPerPage = ref(8);
const userIdToDelete = ref(0);
const userId = ref(0);
const isDialogVisible = ref(false);
const isDialogEditVisible = ref(false);
const route = useRoute("contract-contract_id-guarantor");
const $toast = useToast();

const user = ref({
  name: "",
  email: "",
  password: "",
  role: "",
});
const roles = [
  { value: "admin", title: "Administrateur" },
  { value: "credit_analyst", title: "Analyst crédit" },
  { value: "head_credit", title: "Head credit" },
  { value: "credit_admin", title: "Admin crédit" },
  { value: "operation", title: "Opérations" },
  { value: "md", title: "MD" },
  { value: "caf", title: "CAF" },
  { value: "ca", title: "CA" },
];
const getResetPvError = () => {
  return {
    email: "",
    password: "",
    role: "",
    name: "",
  };
};
const userError = ref(getResetPvError());

const updateOptions = (options) => {
  page.value = options.page;
};
const { data: userData, execute: fetchUsers } = await useApi(
  createUrl("/user", {
    query: {
      // search: searchQuery,
      // with_verbal_trial: 1,
      // contract_id: route.params.contract_id,
      page: page,
    },
  })
);
const apiDelete = async (id) => {
  await $api(`user/${id}`, { method: "DELETE" });
  let instance = $toast.success("Utilisateur supprimé!!", {
    position: "top-right",
  });
  fetchUsers();
};

// const getUser = async () => {
//   // console.log(
//   //   "Récupération des données pour l'utilisateur avec ID:",
//   //   userId.value
//   // ); // Log pour débogage
//   if (userId.value) {
//     const { data } = await useApi(`/user/${userId.value}`);
//     user.value = data.value;
//     console.log("userData found", data); // Assigner les données récupérées à l'utilisateur
//   }
// };
const getUser = async () => {
  if (userId.value) {
    axios
      .get(`/api/user/${userId.value}`, {
        headers: {
          Authorization: `Bearer ${userToken}`,
        },
      })
      .then(({ data }) => {
        // console.log("voici", data);
        user.value.name = data.data.user.name;
        user.value.email = data.data.user.email;
        user.value.role = data.data.user.profile;
        user.value.password = data.data.user.password;
      });
  }
};

const editUser = async () => {
  axios
    .put(
      `/api/user/${userId.value}`,
      {
        name: user.value.name,
        full_name: user.value.name,
        email: user.value.email,
        profile: user.value.role,
        password: user.value.password,
        activated: 1,
        password_change_required: 0,
      },
      {
        headers: {
          Authorization: `Bearer ${userToken}`,
        },
      }
    )
    .then((res) => {
      if (res.status == 200) {
        let instance = $toast.success("Utilisateur modifié!!", {
          position: "top-right",
        });
        isDialogEditVisible.value = false;
        fetchUsers();
      }
    });
};

const openModal = (id) => {
  userId.value = id; // Mettre à jour l'ID de l'utilisateur
  getUser();
  isDialogEditVisible.value = true; // Ouvrir le modal
};

// watch(userIdToDelete, (newId) => {
//   getUser();
// });
const userList = computed(() => userData.value.data);
const totalusers = computed(() => userData.value.total);
const lastPage = computed(() => userData.value.last_page);

const headers = [
  {
    title: "Nom complet",
    key: "full_name",
  },
  {
    title: "Email",
    key: "email",
  },
  {
    title: "Profil",
    key: "profile_fr",
  },
  {
    title: "Actions",
    key: "actions",
    sortable: false,
  },
];
</script>
<template>
  <VCard>
    <VCardText>
      <VRow>
        <VCardText>
          <h2>Liste des utilisateurs</h2>
        </VCardText>
      </VRow>
    </VCardText>
  </VCard>
  <VCard title="Utilisateurs" class="mb-6 mt-2">
    <div class="ms-2 d-flex gap-4 flex-wrap align-center items-end">
      <!-- 👉 Export button -->

      <VBtn
        v-if="$can('create', 'guarantor')"
        color="primary"
        prepend-icon="tabler-plus"
        :to="{
          name: 'user-add',
        }"
      >
        Ajouter
      </VBtn>
    </div>
    <VDivider class="mt-4" />

    <VDataTableServer
      v-model:items-per-page="itemsPerPage"
      v-model:page="page"
      :headers="headers"
      :items="userList"
      :items-length="totalusers"
      class="text-no-wrap"
      @update:options="updateOptions"
    >
      <template #item.actions="{ item }">
        <IconBtn @click="openModal(item.id)">
          <VTooltip
            activator="parent"
            transition="scroll-x-transition"
            location="top"
            v-if="$can('update', 'user')"
            >Modifier</VTooltip
          >
          <VIcon icon="tabler-edit" />
        </IconBtn>
        <IconBtn
          @click="
            userIdToDelete = item.id;
            isDialogVisible = true;
          "
        >
          <VTooltip
            activator="parent"
            transition="scroll-x-transition"
            location="top"
            v-if="$can('delete', 'guarantor')"
            @click="
              userIdToDelete = item.id;
              isDialogVisible = true;
            "
            >Supprimer</VTooltip
          >
          <VIcon icon="tabler-trash" color="error" />
        </IconBtn>
      </template>

      <template #bottom>
        <VDivider />

        <div
          class="d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3"
        >
          <p class="text-sm text-medium-emphasis mb-0">
            {{ paginationMeta({ page, itemsPerPage }, totalusers) }}
          </p>

          <VPagination
            v-model="page"
            :length="lastPage"
            :total-visible="$vuetify.display.xs ? 1 : Math.min(lastPage, 5)"
          >
            <template #prev="slotProps">
              <VBtn
                variant="tonal"
                color="default"
                v-bind="slotProps"
                :icon="false"
              >
                <VIcon start icon="tabler-arrow-left" />
                Précedent
              </VBtn>
            </template>

            <template #next="slotProps">
              <VBtn
                variant="tonal"
                color="default"
                v-bind="slotProps"
                :icon="false"
              >
                Suivant
                <VIcon end icon="tabler-arrow-right" />
              </VBtn>
            </template>
          </VPagination>
        </div>
      </template>
    </VDataTableServer>
  </VCard>

  <VDialog v-model="isDialogVisible" class="v-dialog-sm">
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

    <!-- Dialog Content -->
    <VCard title="Suppression">
      <VCardText>
        Etes vous sûr de vouloir supprimer cet utilisateur?
      </VCardText>

      <VCardText class="d-flex justify-end gap-3 flex-wrap">
        <VBtn
          color="secondary"
          variant="tonal"
          @click="isDialogVisible = false"
        >
          Annuler
        </VBtn>
        <VBtn
          @click="
            apiDelete(userIdToDelete);
            isDialogVisible = false;
          "
        >
          Supprimer
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
  <VDialog v-model="isDialogEditVisible" class="v-dialog-sm">
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="isDialogEditVisible = !isDialogEditVisible" />

    <!-- Dialog Content -->
    <VCard title="Modification">
      <!-- <VCardText>
        Etes vous sûr de vouloir supprimer cet utilisateur?
      </VCardText> -->
      <VForm ref="refForm" @submit.prevent="editUser">
        <VRow>
          <VCol md="12">
            <!-- 👉 PV Information -->
            <VCard class="mb-6" title="Information sur l'utilisateur">
              <VCardText>
                <VRow>
                  <VCol cols="12" md="6" lg="4">
                    <AppTextField
                      v-model="user.name"
                      :error-messages="userError.name"
                      label="Votre nom"
                      :rules="[requiredValidator]"
                    />
                  </VCol>
                  <VCol cols="12" md="6" lg="4">
                    <AppTextField
                      v-model="user.email"
                      :error-messages="userError.email"
                      label="Votre e-mail"
                      :rules="[requiredValidator]"
                    />
                  </VCol>
                  <VCol cols="12" md="6" lg="4">
                    <AppSelect
                      v-model="user.role"
                      :items="roles"
                      :error-messages="userError.role"
                      label="Rôle"
                      placeholder="Ex: Administrateur"
                      :rules="[requiredValidator]"
                    />
                  </VCol>
                  <VCol cols="12" md="6" lg="4">
                    <AppTextField
                      v-model="user.password"
                      :error-messages="userError.password"
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
    </VCard>
  </VDialog>
</template>
