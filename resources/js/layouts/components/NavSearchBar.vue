<script setup>
import Shepherd from 'shepherd.js'
import { withQuery } from 'ufo'
import { useConfigStore } from '@core/stores/config'

defineOptions({
  // 👉 Is App Search Bar Visible
  inheritAttrs: false,
})

const configStore = useConfigStore()
const isAppSearchBarVisible = ref(false)

// 👉 Default suggestions
const suggestionGroups = [
  {
    title: 'Courants',
    content: [
      {
        icon: 'tabler-align-box-left-top',
        title: 'PV',
        url: { name: 'pv' },
      },
      {
        icon: 'tabler-align-box-left-top',
        title: 'Contract',
        url: { name: 'contract' },
      },
    ],
  },
  {
    title: 'Menu',
    content: [
      {
        icon: 'tabler-align-box-left-top',
        title: 'PV',
        url: { name: 'pv' },
      },
      {
        icon: 'tabler-align-box-left-top',
        title: 'Contract',
        url: { name: 'contract' },
      },
    ],
  },
]

// 👉 No Data suggestion
const noDataSuggestions = [
  {
    icon: 'tabler-align-box-left-top',
    title: 'PV',
    url: { name: 'pv' },
  },
  {
    icon: 'tabler-align-box-left-top',
    title: 'Contract',
    url: { name: 'contract' },
  },
]

const searchQuery = ref('')
const router = useRouter()
const searchResult = ref([])

const fetchResults = async () => {
  const { data } = await useApi(withQuery('/app-bar/search', { q: searchQuery.value }))

  searchResult.value = data.value
}

watch(searchQuery, fetchResults)

const redirectToSuggestedOrSearchedPage = selected => {
  router.push(selected.url)
  isAppSearchBarVisible.value = false
  searchQuery.value = ''
}

const LazyAppBarSearch = defineAsyncComponent(() => import('@core/components/AppBarSearch.vue'))
</script>

<template>
  <div
    class="d-flex align-center cursor-pointer"
    v-bind="$attrs"
    style="user-select: none;"
    @click="isAppSearchBarVisible = !isAppSearchBarVisible"
  >
    <!-- 👉 Search Trigger button -->
    <!-- close active tour while opening search bar using icon -->
    <IconBtn
      class="me-1"
      @click="Shepherd.activeTour?.cancel()"
    >
      <VIcon
        size="26"
        icon="tabler-search"
      />
    </IconBtn>

    <span
      v-if="configStore.appContentLayoutNav === 'vertical'"
      class="d-none d-md-flex align-center text-disabled"
      @click="Shepherd.activeTour?.cancel()"
    >
      <span class="me-3">Search</span>
      <span class="meta-key">&#8984;K</span>
    </span>
  </div>

  <!-- 👉 App Bar Search -->
  <LazyAppBarSearch
    v-model:isDialogVisible="isAppSearchBarVisible"
    :search-results="searchResult"
    @search="searchQuery = $event"
  >
    <!-- suggestion -->
    <template #suggestions>
      <VCardText class="app-bar-search-suggestions h-100 pa-10">
        <VRow
          v-if="suggestionGroups"
          class="gap-y-4"
        >
          <VCol
            v-for="suggestion in suggestionGroups"
            :key="suggestion.title"
            cols="12"
            sm="6"
            class="ps-6"
          >
            <p class="text-xs text-disabled text-uppercase">
              {{ suggestion.title }}
            </p>
            <VList class="card-list">
              <VListItem
                v-for="item in suggestion.content"
                :key="item.title"
                link
                :title="item.title"
                class="app-bar-search-suggestion"
                @click="redirectToSuggestedOrSearchedPage(item)"
              >
                <template #prepend>
                  <VIcon
                    :icon="item.icon"
                    size="20"
                    class="me-2"
                  />
                </template>
              </VListItem>
            </VList>
          </VCol>
        </VRow>
      </VCardText>
    </template>
    <!-- no data suggestion -->
    <template #noDataSuggestion>
      <div class="mt-8">
        <span class="d-flex justify-center text-disabled">Try searching for</span>
        <h6
          v-for="suggestion in noDataSuggestions"
          :key="suggestion.title"
          class="app-bar-search-suggestion text-sm font-weight-regular cursor-pointer mt-3"
          @click="redirectToSuggestedOrSearchedPage(suggestion)"
        >
          <VIcon
            size="20"
            :icon="suggestion.icon"
            class="me-3"
          />
          <span class="text-sm">{{ suggestion.title }}</span>
        </h6>
      </div>
    </template>
    <!-- search result -->
    <template #searchResult="{ item }">
      <VListSubheader class="text-disabled">
        {{ item.title }}
      </VListSubheader>
      <VListItem
        v-for="list in item.children"
        :key="list.title"
        link
        @click="redirectToSuggestedOrSearchedPage(list)"
      >
        <template #prepend>
          <VIcon
            size="20"
            :icon="list.icon"
            class="me-3"
          />
        </template>
        <template #append>
          <VIcon
            size="20"
            icon="tabler-corner-down-left"
            class="enter-icon text-disabled"
          />
        </template>
        <VListItemTitle>
          {{ list.title }}
        </VListItemTitle>
      </VListItem>
    </template>
  </LazyAppBarSearch>
</template>

<style lang="scss" scoped>
@use "@styles/variables/_vuetify.scss";

.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>
