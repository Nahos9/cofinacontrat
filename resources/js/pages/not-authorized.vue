<script setup>
import pages401 from '@images/pages/401.png'
import miscMaskDark from '@images/pages/misc-mask-dark.png'
import miscMaskLight from '@images/pages/misc-mask-light.png'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'

definePage({
  alias: '/pages/misc/not-authorized',
  meta: {
    layout: 'blank',
    public: true,
  },
})

const authThemeMask = useGenerateImageVariant(miscMaskLight, miscMaskDark)
</script>

<template>
  <div class="misc-wrapper">
    <ErrorHeader status-code="401" title="Vous n'etes pas autorisé 🔐" description="Vous n'êtes pas autorisé à afficher cette page en utilisant les informations d'identification que vous avez fournies lors de la connexion.
        Merci de contacter l'administrateur de votre site." />

    <VBtn class="mt-2 mb-10" to="/">
      Retour à l'entrée
    </VBtn>

    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg :src="pages401" alt="Coming Soon" :max-width="170" class="mx-auto" />
    </div>

    <VImg :src="authThemeMask" class="misc-footer-img d-none d-md-block" />
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
