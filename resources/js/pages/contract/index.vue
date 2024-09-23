<!-- eslint-disable camelcase -->

<script setup>
definePage({
  meta: {
    action: "read",
    subject: "contract",
  },
});
import { VDataTableServer } from "vuetify/labs/VDataTable";
import { paginationMeta } from "@api-utils/paginationMeta";
import JsFileDownloader from "js-file-downloader";
import { $api } from "@/utils/api";
import { useRouter } from "vue-router";

const router = useRouter();
const selectedType = ref();
const searchQuery = ref("");
const refInputEl = ref();
const uploadState = ref("signed_contract");
const selectedItemId = ref(0);
const isActionDialogVisible = ref(false);
const actionTitle = ref("");
const actionText = ref("");
const actionButtonText = ref("");
const actionFunction = ref();
const actionComment = ref("");
const commentPresence = ref(false);
const actionStatus = ref("waiting");
const loadings = ref([]);
const headers = [
  {
    title: "Numéro comitée",
    key: "verbal_trial.committee_id",
  },
  {
    title: "Admin Crédit",
    key: "creator.full_name",
  },
  {
    title: "Nom client",
    key: "verbal_trial.applicant_full_name",
  },
  {
    title: "Type de contrat",
    key: "type",
  },
  {
    title: "Montant",
    key: "verbal_trial.amount",
  },
  {
    title: "Observations",
    key: "observations",
  },
  {
    title: "Actions",
    key: "actions",
    sortable: false,
  },
];
const typeList = {
  company: "Société",
  individual_business: "Entreprise Individuel",
  particular: "Particulier",
};

const load = (i) => {
  loadings.value[i] = true;
  setTimeout(() => {
    loadings.value[i] = false;
  }, 1000);
};

const itemsPerPage = ref(8);
const page = ref(1);

const updateOptions = (options) => {
  page.value = options.page;
};

const { data: contractData, execute: fetchContracts } = await useApi(
  createUrl("/contract", {
    query: {
      search: searchQuery,
      type: selectedType,
      page: page,
      with_type_of_credit: 1,
      with_company: 1,
      with_individual_business: 1,
      with_creator: 1,
      // has_upload_completed: 1,
      has_cat: 0,
    },
  })
);

// const downloadFile = async (url, fileName) => {
//   try {
//     new JsFileDownloader({
//       url: url,
//       headers: [
//         {
//           name: "Authorization",
//           value: `Bearer ${useCookie("userToken").value}`,
//         },
//         { name: "Accept", value: `application/json` },
//       ],
//       nameCallback: function (name) {
//         return fileName;
//       },
//     });
//     fetchContracts();
//   } catch (error) {
//     console.error("Erreur lors du téléchargement:", error);
//   }
// };
const downloadFile = async (url, fileName) => {
  try {
    const response = await fetch(url, {
      headers: {
        Authorization: `Bearer ${useCookie("userToken").value}`,
        Accept: "application/pdf",
      },
    });

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const blob = await response.blob();

    // Vérification du contenu du fichier
    const text = await blob.text();
    console.log("Contenu du fichier téléchargé :", text);

    if (blob.type !== "application/pdf") {
      throw new Error("Le fichier téléchargé n'est pas un PDF.");
    }

    const fileUrl = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = fileUrl;
    a.download = fileName;
    document.body.appendChild(a);
    a.click();
    a.remove();
  } catch (error) {
    console.error("Erreur lors du téléchargement:", error);
  }
};

const uploadFile = async (id, event) => {
  const { files } = event.target;
  if (files && files.length === 1) {
    const reader = new FileReader();
    reader.onload = async () => {
      const base64Image = reader.result;
      try {
        const response = await fetch(`/api/contract/upload/${id}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${useCookie("userToken").value}`,
          },
          body: JSON.stringify({
            [uploadState.value]: base64Image,
          }),
        });

        if (response.ok) {
          console.log("Document envoyé avec succès.");
          fetchContracts();
        } else {
          console.error("Échec de l'envoi du document.");
        }
      } catch (error) {
        console.error("Erreur lors de l'envoi du document:", error);
      }
    };
    reader.readAsDataURL(files[0]);
  } else {
    console.error("Veuillez sélectionner un seul fichier.");
  }
};

const apiDelete = async (id) => {
  await $api(`contract/${id}`, { method: "DELETE" });
  fetchContracts();
};

const apiChangeStatus = async (id) => {
  await $api(`contract/change-status/${id}`, {
    method: "PUT",
    body: { status: actionStatus.value, comment: actionComment.value },
  });
  actionComment.value = "";
  if (actionStatus.value == "validated") {
    router.push(`/cat/add?id=${id}`);
  }
  fetchContracts();
};

const contractList = computed(() => contractData.value.data);
// console.log("okkk", contractData.value.data);
const totalPv = computed(() => contractData.value.total);
const lastPage = computed(() => contractData.value.last_page);

const downloadFileDirectly = async (id) => {
  const url = `/api/contract/download/${id}`;
  const fileName = `contracts-${id}.zip`;
  try {
    const response = await fetch(url, {
      headers: {
        Authorization: `Bearer ${useCookie("userToken").value}`,
        Accept: "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("Erreur lors du téléchargement du fichier");
    }

    const blob = await response.blob();
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error("Erreur lors du téléchargement:", error);
  }
};
</script>

<template>
  <div>
    <VCard class="mb-6">
      <VCardText>
        <VRow>
          <VCardText>
            <h2>Liste des contrats</h2>
          </VCardText>
        </VRow>
      </VCardText>
    </VCard>

    <VCard title="Filtres" class="mb-6">
      <VCardText>
        <VRow>
          <VCol cols="12" sm="4">
            <AppSelect
              v-model="selectedType"
              placeholder="Type de contrat"
              :items="[
                { value: 'company', title: 'Société' },
                { value: 'particular', title: 'Particulier' },
                {
                  value: 'individual_business',
                  title: 'Entreprise Individuel',
                },
              ]"
              clearable
              clear-icon="tabler-x"
            />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider class="my-4" />

      <div class="d-flex flex-wrap gap-4 mx-5">
        <div class="d-flex align-center">
          <AppTextField
            v-model="searchQuery"
            placeholder="Rechercher un contrat"
            density="compact"
            style="inline-size: 200px"
            class="me-3"
          />
        </div>

        <VSpacer />
        <div class="d-flex gap-4 flex-wrap align-center">
          <VBtn
            variant="tonal"
            color="secondary"
            prepend-icon="tabler-download"
          >
            Export
          </VBtn>

          <VBtn
            v-if="$can('create', 'contract')"
            color="primary"
            prepend-icon="tabler-plus"
            :to="{ name: 'contract-add' }"
          >
            Ajouter
          </VBtn>
          <VBtn
            :loading="loadings[3]"
            :disabled="loadings[3]"
            prepend-icon="tabler-refresh"
            @click="
              fetchContracts();
              load(3);
            "
          >
            Recharger
            <template #loader>
              <span class="custom-loader">
                <VIcon icon="tabler-refresh" />
              </span>
            </template>
          </VBtn>
        </div>
      </div>

      <VDivider class="mt-4" />

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :headers="headers"
        :items="contractList"
        :items-length="totalPv"
        class="text-no-wrap"
        @update:options="updateOptions"
      >
        <template #item.type="{ item }">
          {{ typeList[item.type] }}
        </template>

        <template #item.verbal_trial.amount="{ item }">
          {{
            String(item.verbal_trial.amount).replace(
              /\B(?=(\d{3})+(?!\d))/g,
              " "
            )
          }}
          F CFA
        </template>

        <template #item.observations="{ item }">
          <VList v-if="item.observations.length > 0" density=" compact">
            <VListItem v-for="observation in item.observations">
              <VListItemTitle>
                <VChip label>
                  {{ observation }}
                </VChip>
              </VListItemTitle>
            </VListItem>
          </VList>

          <VList density="compact" v-if="item.observations.length == 0">
            <VListItem>
              <VChip
                label
                :color="
                  {
                    validated: 'success',
                    rejected: 'error',
                    waiting: 'warning',
                  }[item.status]
                "
              >
                <VTooltip
                  v-if="item.status_observation"
                  activator="parent"
                  transition="scroll-x-transition"
                  location="start"
                  >Raison: {{ item.status_observation }}</VTooltip
                >
                {{ item.status == "validated" ? "Dossier validé" : null }}
                {{
                  item.status == "waiting"
                    ? "Dossier en attente de validation"
                    : null
                }}
                {{ item.status == "rejected" ? "Dossier rejeté" : null }}
              </VChip>
            </VListItem>
          </VList>
        </template>

        <template #item.actions="{ item }">
          <span>
            <IconBtn :to="{ name: 'contract-id', params: { id: item.id } }">
              <VTooltip
                activator="parent"
                transition="scroll-x-transition"
                location="start"
                >Details</VTooltip
              >
              <VIcon icon="tabler-eye" />
            </IconBtn>
            <VBtn icon variant="text" size="small" color="medium-emphasis">
              <VIcon size="24" icon="tabler-dots-vertical" />
              <VMenu activator="parent">
                <VList>
                  <input
                    ref="refInputEl"
                    type="file"
                    name="signed_contract"
                    accept=".pdf,.png,.jpg"
                    hidden
                    @input="uploadFile(item.id, $event)"
                  />

                  <VBadge
                    v-if="$can('read', 'guarantor')"
                    inline
                    :content="item.guarantors_count"
                  >
                    <VListItem
                      :to="{
                        name: 'contract-contract_id-guarantor',
                        params: { contract_id: item.id },
                      }"
                    >
                      <template #prepend>
                        <VIcon icon="tabler-users" />
                      </template>

                      <VListItemTitle> Voir les Cautions </VListItemTitle>
                    </VListItem>
                  </VBadge>
                  <VListItem
                    v-if="$can('read', 'pv')"
                    :to="{
                      name: 'pv-id',
                      params: { id: item.verbal_trial.id },
                    }"
                  >
                    <template #prepend>
                      <VIcon icon="tabler-eye" />
                    </template>

                    <VListItemTitle>Voir le Pv</VListItemTitle>
                  </VListItem>

                  <div v-if="$can('download', 'contract')">
                    <VDivider />
                    <!-- Télécharger contrat non-signé -->
                    <VListItem @click="downloadFileDirectly(item.id)">
                      <template #prepend>
                        <VIcon icon="tabler-download" />
                      </template>
                      <VListItemTitle
                        >Télécharger Contrat non-signé</VListItemTitle
                      >
                    </VListItem>
                    <!-- Télécharger contrat signé -->
                    <VListItem
                      v-if="item.signed_contract_path"
                      @click="
                        downloadFile(
                          item.signed_contract_path,
                          `Contrat-${
                            item.signed_contract_path.split('/').slice(-1)[0]
                          }`
                        )
                      "
                    >
                      <template #prepend>
                        <VIcon icon="tabler-download" />
                      </template>
                      <VListItemTitle>Télécharger Contrat signé</VListItemTitle>
                    </VListItem>
                    <!-- Télécharger billet à ordre non-signé -->
                    <!-- <VListItem
                      @click="
                        downloadFile(
                          `/api/contract/promissory-note/download/${item.id}`,
                          `Billet-à-ordre-${item.verbal_trial.committee_id}.docx`
                        )
                      "
                    >
                      <template #prepend>
                        <VIcon icon="tabler-download" />
                      </template>
                      <VListItemTitle
                        >Télécharger Billet à ordre non signé</VListItemTitle
                      >
                    </VListItem> -->
                    <!-- Télécharger billet à ordre signé -->
                    <!-- <VListItem
                      v-if="item.signed_promissory_note_path"
                      @click="
                        downloadFile(
                          item.signed_promissory_note_path,
                          `Billet-à-ordre-${
                            item.signed_promissory_note_path
                              .split('/')
                              .slice(-1)[0]
                          }`
                        )
                      "
                    >
                      <template #prepend>
                        <VIcon icon="tabler-download" />
                      </template>
                      <VListItemTitle
                        >Télécharger Billet à ordre signé</VListItemTitle
                      >
                    </VListItem> -->
                  </div>

                  <div v-if="$can('upload', 'contract')">
                    <VDivider />
                    <!-- Ajouter Contrat signé -->
                    <VListItem
                      v-if="
                        item.signed_contract_path == null ||
                        item.status == 'rejected'
                      "
                      @click="
                        uploadState = 'signed_contract';
                        refInputEl?.click();
                      "
                    >
                      <template #prepend>
                        <VIcon icon="tabler-cloud-upload" />
                      </template>
                      <VListItemTitle color="error"
                        >Ajouter contrat signé</VListItemTitle
                      >
                    </VListItem>
                    <!-- Ajouter Billet à ordre -->
                    <!-- <VListItem
                      v-if="
                        item.signed_promissory_note_path == null ||
                        item.status == 'rejected'
                      "
                      @click="
                        uploadState = 'signed_promissory_note';
                        refInputEl?.click();
                      "
                    >
                      <template #prepend>
                        <VIcon icon="tabler-cloud-upload" />
                      </template>
                      <VListItemTitle
                        >Ajouter billet à ordre signé</VListItemTitle
                      >
                    </VListItem> -->
                  </div>
                </VList>
              </VMenu>
            </VBtn>
          </span>

          <span v-if="$can('update', 'contract') || $can('delete', 'contract')">
            <VDivider />
            <IconBtn
              v-if="$can('update', 'contract')"
              :to="{ name: 'contract-edit-id', params: { id: item.id } }"
              :disabled="item.status == 'validated'"
            >
              <VTooltip
                activator="parent"
                transition="scroll-x-transition"
                location="start"
                >Modifier</VTooltip
              >
              <VIcon icon="tabler-edit" />
            </IconBtn>
            <IconBtn
              v-if="$can('delete', 'contract')"
              @click="
                selectedItemId = item.id;
                (actionTitle = 'Supprimer le contrat'),
                  (actionText = 'Voulez vous vraiment supprimer ce contrat?'),
                  (actionFunction = apiDelete);
                actionButtonText = 'Supprimer';
                commentPresence = false;
                isActionDialogVisible = true;
              "
            >
              <VTooltip
                activator="parent"
                transition="scroll-x-transition"
                location="end"
                >Supprimer</VTooltip
              >
              <VIcon icon="tabler-trash" color="error" />
            </IconBtn>
          </span>

          <span
            v-if="
              $can('reject', 'contract') ||
              $can('validate', 'contract') ||
              $can('create', 'cat')
            "
          >
            <VDivider />
            <IconBtn
              v-if="
                $can('reject', 'contract') &&
                item.status != 'rejected' &&
                item.observations.length == 0
              "
              @click="
                selectedItemId = item.id;
                (actionTitle = 'Rejeter le contrat'),
                  (actionText = 'Voulez vous vraiment rejeter ce contrat?'),
                  (actionFunction = apiChangeStatus);
                actionButtonText = 'Rejeter';
                commentPresence = true;
                actionStatus = 'rejected';
                isActionDialogVisible = true;
              "
            >
              <VTooltip
                activator="parent"
                transition="scroll-x-transition"
                location="start"
                >Rejeter</VTooltip
              >
              <VIcon icon="tabler-x" color="error" />
            </IconBtn>
            <IconBtn
              v-if="
                $can('validate', 'contract') &&
                item.status == 'waiting' &&
                item.observations.length == 0
              "
              @click="
                selectedItemId = item.id;
                (actionTitle = 'Valider le contrat'),
                  (actionText = 'Voulez vous vraiment valider ce contrat?'),
                  (actionFunction = apiChangeStatus);
                actionButtonText = 'Valider';
                commentPresence = false;
                actionStatus = 'validated';
                isActionDialogVisible = true;
              "
            >
              <VTooltip
                activator="parent"
                transition="scroll-x-transition"
                location="end"
                >Valider</VTooltip
              >
              <VIcon icon="tabler-check" color="success" />
            </IconBtn>
            <IconBtn
              v-if="$can('create', 'cat') && item.status == 'validated'"
              :to="{ name: 'cat-add', query: { id: item.id } }"
            >
              <VTooltip
                activator="parent"
                transition="scroll-x-transition"
                location="end"
                >Créer le CAT</VTooltip
              >
              <VIcon icon="tabler-file-plus" color="success" />
            </IconBtn>
          </span>
        </template>

        <template #bottom>
          <VDivider />

          <div
            class="d-flex align-center justify-space-between flex-wrap gap-3 pa-5 pt-3"
          >
            <p class="text-sm text-medium-emphasis mb-0">
              {{ paginationMeta({ page, itemsPerPage }, totalPv) }}
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

    <VDialog v-model="isActionDialogVisible" class="v-dialog-sm">
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="isActionDialogVisible = !isActionDialogVisible" />

      <!-- Dialog De suppression -->
      <VCard :title="actionTitle">
        <VCardText>
          {{ actionText }}

          <AppTextarea
            v-if="commentPresence"
            class="mt-3"
            v-model="actionComment"
            label="Commentaire"
            placeholder="Ex: RAS"
          />
        </VCardText>

        <VCardText class="d-flex justify-end gap-3 flex-wrap">
          <VBtn
            color="secondary"
            variant="tonal"
            @click="isActionDialogVisible = false"
          >
            Annuler
          </VBtn>
          <VBtn
            @click="
              actionFunction(selectedItemId);
              isActionDialogVisible = false;
            "
          >
            {{ actionButtonText }}
          </VBtn>
        </VCardText>
      </VCard>
    </VDialog>
  </div>
</template>

<style lang="scss" scoped>
.custom-loader {
  display: flex;
  animation: loader 1s infinite;
}

@keyframes loader {
  from {
    transform: rotate(0);
  }

  to {
    transform: rotate(360deg);
  }
}
</style>
