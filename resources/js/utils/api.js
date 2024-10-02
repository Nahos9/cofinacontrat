import { ofetch } from "ofetch";

const $api = ofetch.create({
  baseURL: "/api",
  onRequest: async ({ options }) => {
    options.headers = {
      ...options.headers,
      Accept: "application/json",
    };

    const userToken = useCookie("userToken").value;
    if (userToken) {
      options.headers = {
        ...options.headers,
        Authorization: `Bearer ${userToken}`,
      };
    }
  },
  onResponse: async ({ response }) => {
    if (response.status === 401) {
      useCookie("userToken").value = null;
      useCookie("userData").value = null;
      useCookie("userAbilityRules").value = null;
      const ability = useAbility();
      ability.update([]);
      window.location.href = "/login";
    }
  },
});

export { $api };
