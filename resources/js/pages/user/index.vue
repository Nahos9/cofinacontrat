<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

definePage({
  meta: {
    // action: "read" || "historical",
    subject: "pv",
  },
});

// const users = ref([]);
const users = [];
const userToken = useCookie("userToken").value;

// const header = [
//   {
//     title: "Nom complet",
//     key: "full_name",
//   },
//   {
//     title: "Email",
//     key: "email",
//   },
//   {
//     title: "Profil",
//     key: "profile_fr",
//   },
// ];
const getUsers = async () => {
  // Changez la fonction pour être asynchrone
  try {
    const res = await axios.get("/api/user", {
      // Attendez la réponse
      headers: {
        Authorization: `Bearer ${userToken}`,
      },
    });
    users.push(res.data.data); // Stockez les utilisateurs dans la référence
  } catch (error) {
    console.error("Erreur lors de la récupération des utilisateurs:", error);
  }
};

// const usersList = computed(() => users.value);
// console.log(usersList);
getUsers();
console.log("users", users);
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
    <VDivider class="mt-4" />
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nom complet</th>
          <th scope="col">Email</th>
          <th scope="col">Profil</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(user, index) in users[0]" :key="user.id">
          <!-- Boucle sur les utilisateurs -->
          <th scope="row">{{ index + 1 }}</th>
          <!-- Affiche l'index -->
          <td>{{ user.full_name }}</td>
          <!-- Affiche le nom complet -->
          <td>{{ user.email }}</td>
          <!-- Affiche l'email -->
          <td>{{ user.profile_fr }}</td>
          <!-- Affiche le profil -->
        </tr>
      </tbody>
    </table>
  </VCard>
</template>
