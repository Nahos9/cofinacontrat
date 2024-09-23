<script setup>
const router = useRouter()

const isCurrentPasswordVisible = ref(false)
const isNewPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const passwordData = ref({
  old_password: '',
  new_password: '',
  new_password_confirmation: '',
})

const getResetPasswordError = () => {
  return {
    old_password: '',
    new_password: '',
    new_password_confirmation: '',
  }
}

const passwordErrors = ref(getResetPasswordError())

const passwordRequirements = [
  'Au minimum 8 charactères',
  'Au moins une majuscule',
  'Au moins une minuscule',
  'Au moins un chiffre',
  'Au moins un caractère spécial',
]

const refForm = ref()
const onSubmit = () => {
  refForm.value?.validate().then(async ({ valid }) => {
    if (valid) {
      const res = await $api('/user/update-password', {
        method: 'PUT',
        body: passwordData.value,
      })

      passwordErrors.value = getResetPasswordError()
      if (res.status == 200) {
        useCookie('userToken').value = null
        useCookie('userData').value = null
        await router.push('/login')
      } else {
        for (const key in res.errors) {
          res.errors[key].forEach(message => {
            passwordErrors.value[key] += message + "\n"
          })
        }
      }
      nextTick(() => {
        // refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}
</script>

<template>
  <VRow>
    <!-- SECTION: Change Password -->
    <VCol cols="12">
      <VCard title="Modification du mot de passe">
        <VForm ref="refForm" @submit.prevent="onSubmit">
          <VCardText class="pt-0">
            <!-- 👉 Current Password -->
            <VRow>
              <VCol cols="12" md="12">
                <!-- 👉 old_password password -->
                <AppTextField v-model="passwordData.old_password" :errorMessages="passwordErrors.old_password"
                  :type="isCurrentPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isCurrentPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Mot de passe actuel" autocomplete="on" placeholder="············"
                  @click:append-inner="isCurrentPasswordVisible = !isCurrentPasswordVisible" />
              </VCol>
            </VRow>

            <!-- 👉 New Password -->
            <VRow>
              <VCol cols="12" md="12">
                <!-- 👉 new_password password -->
                <AppTextField v-model="passwordData.new_password" :errorMessages="passwordErrors.new_password"
                  :type="isNewPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isNewPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Nouveau mot de passe" autocomplete="on" placeholder="············"
                  @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible" />
              </VCol>

              <VCol cols="12" md="12">
                <!-- 👉 confirm password -->
                <AppTextField v-model="passwordData.new_password_confirmation"
                  :errorMessages="passwordErrors.new_password_confirmation"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Confirmation du nouveau mot de passe" autocomplete="on" placeholder="············"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible" />
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 Password Requirements -->
          <VCardText>
            <h6 class="text-base font-weight-medium mb-3">
              Exigences du mot de passe:
            </h6>

            <VList class="card-list">
              <VListItem v-for="item in passwordRequirements" :key="item" :title="item" class="text-medium-emphasis">
                <template #prepend>
                  <VIcon size="8" icon="tabler-circle" class="me-3" />
                </template>
              </VListItem>
            </VList>
          </VCardText>

          <!-- 👉 Action Buttons -->
          <VCardText class="d-flex flex-wrap gap-4">
            <VBtn type="submit">Enregistrer</VBtn>

            <VBtn type="reset" color="secondary" variant="tonal">
              Réinitialiser
            </VBtn>
          </VCardText>
        </VForm>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 5px;
}
</style>
