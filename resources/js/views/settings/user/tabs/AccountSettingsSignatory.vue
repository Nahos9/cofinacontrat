<script setup>
import { useCookie } from '@/@core/composable/useCookie';

const accountData = {
  avatarImg: useCookie('userData').value["signatory"],
}
const error = ref("")

const refInputEl = ref()
const accountDataLocal = ref(structuredClone(accountData))


const changeAvatar = file => {
  const fileReader = new FileReader()
  const { files } = file.target
  if (files && files.length) {
    fileReader.readAsDataURL(files[0])
    fileReader.onload = () => {
      if (typeof fileReader.result === 'string') {
        accountDataLocal.value.avatarImg = fileReader.result
      }
    }
  }
}

const resetAvatar = () => {
  accountDataLocal.value.avatarImg = accountData.avatarImg
}

const updateAvatar = async () => {
  const res = await $api('/user/update-signatory/' + useCookie('userData').value["id"], {
    method: 'PUT',
    body: {
      signatory: accountDataLocal.value.avatarImg,
    },
  })

  error.value = ""
  if (res.status == 200) {
    useCookie('userData').value.signatory = "http://credit.cofina.localhost" + res.data.user.signatory_path;
    snackbarColor.value = "success"
    snackbarMessage.value = "Signature uploadé avec succès"
    isSnackbarScrollReverseVisible.value = true
  } else {
    snackbarMessage.value = ""
    snackbarColor.value = "error"
    for (const key in res.errors) {
      res.errors[key].forEach(message => {
        snackbarMessage.value += message + "\n";
      })
    }
    isSnackbarScrollReverseVisible.value = true
  }
}

const isSnackbarScrollReverseVisible = ref(false)
const snackbarMessage = ref("")
const snackbarColor = ref("error")
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Signature du compte">
        <VCardText class="d-flex">
          <!-- 👉 Avatar -->
          <VAvatar rounded size="140" class="me-6" :image="accountDataLocal.avatarImg" />

          <!-- 👉 Upload Photo -->

          <VForm @submit.prevent="updateAvatar" class="d-flex flex-column justify-center gap-4">
            <div class="d-flex flex-wrap gap-2">
              <VBtn color="primary" @click="refInputEl?.click()">
                <VIcon icon="tabler-cloud-upload" class="d-sm-none" />
                <span class="d-none d-sm-block">Ajouter une nouvelle signature</span>
              </VBtn>

              <input ref="refInputEl" type="file" name="file" accept=".jpeg,.png,.jpg,GIF" hidden @input="changeAvatar">

              <VBtn type="reset" color="secondary" variant="tonal" @click="resetAvatar">
                <span class="d-none d-sm-block">Réinitialiser</span>
                <VIcon icon="tabler-refresh" class="d-sm-none" />
              </VBtn>

            </div>

            <p class="text-body-1 mb-0">
              Autorisés JPG, GIF ou PNG. Taille Max of 800K
            </p>
            <VBtn type="submit">Enregistrer</VBtn>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
    <VSnackbar v-model="isSnackbarScrollReverseVisible" transition="scroll-y-reverse-transition" location="bottom end"
      :color="snackbarColor">
      {{ snackbarMessage }}
    </VSnackbar>
  </VRow>
</template>
