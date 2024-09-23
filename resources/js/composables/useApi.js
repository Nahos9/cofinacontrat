import { createFetch } from '@vueuse/core'
import { destr } from 'destr'

const useApi = createFetch({
  baseUrl: '/api',
  fetchOptions: {
    headers: {
      Accept: 'application/json',
    },
  },
  options: {
    refetch: true,
    async beforeFetch({ options }) {
      const userToken = useCookie('userToken').value
      if (userToken) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${userToken}`,
        }
      }

      return { options }
    },
    async afterFetch(ctx) {
      const { data, response } = ctx
      let parsedData = null
      try {
        parsedData = destr(data)
      }
      catch (error) {
        console.error(error)
      }
      return { data: parsedData, response }
    },
    async onFetchError(ctx) {
      const { data, response } = ctx
      let parsedData = null
      try {
        parsedData = destr(data)
      }
      catch (error) {
        console.error(error)
      }

      if (response.status == 401) {
        useCookie('userToken').value = null
        useCookie('userData').value = null
        useCookie('userAbilityRules').value = null
        window.location.href = '/login';
      }
    }
  },
})

export { useApi }
