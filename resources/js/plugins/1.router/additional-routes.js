// 👉 Redirects
export const redirects = [
	// ℹ️ We are redirecting to different pages based on role.
	// NOTE: Role is just for UI purposes. ACL is based on abilities.
	{
		path: '/',
		name: 'index',
		redirect: to => {
			// TODO: Get type from backend
			const userData = useCookie('userData')
			const userRole = userData.value?.role
			if (userRole === 'admin')
				return { name: 'pv' }

			if (userRole === 'credit_analyst')
				return { name: 'pv' }

			if (userRole === 'credit_admin')
				return { name: 'pv' }

			if (userRole === 'head_credit')
				return { name: 'pv' }

			if (userRole === 'operation')
				return { name: 'cat' }

			if (userRole === 'caf')
				return { name: 'contract' }

			if (userRole === 'dex')
				return { name: 'pv' }

			if (userRole === 'legal')
				return { name: 'notification-without-signed-contract' }

			if (userRole === 'ca')
				return { name: 'deadline-postponed' }

			if (userRole === 'md')
				return { name: 'pv' }

			return { name: 'login', query: to.query }
		},
	},
]
