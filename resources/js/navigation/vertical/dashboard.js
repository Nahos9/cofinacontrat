export default [
  {
    title: "Ajouter PV",
    to: "pv-add",
    icon: { icon: "tabler-file-plus" },
    action: "create",
    subject: "pv",
  },
  {
    title: "Contrat",
    icon: { icon: "tabler-writing-sign" },
    action: "read",
    subject: "non-mortgage-contract",
    children: [
      {
        title: "Pv Comité",
        subject: "pv",
        action: "historical",
        children: [
          {
            title: "Sans contrat",
            to: "pv",
            action: "read",
            subject: "pv",
          },
          {
            title: "Historique",
            to: { name: "pv-historical" },
            action: "historical",
            subject: "pv",
            // badgeContent: historicalVpCount.value,
            // badgeClass: 'bg-global-primary',
          },
        ],
      },
      {
        title: "Contract",
        action: "read",
        subject: "contract",
        children: [
          {
            title: "Ajouter",
            to: "contract-add",
            action: "create",
            subject: "contract",
          },
          {
            title: "Sans signature",
            to: "contract",
            action: "read",
            subject: "contract",
          },
          {
            title: "Historique",
            to: "contract-historical",
            action: "historical",
            subject: "contract",
          },
        ],
      },
      {
        title: "CAT",
        subject: "cat",
        action: "read",
        children: [
          {
            title: "Ajouter",
            to: "cat-add",
            action: "create",
            subject: "cat",
          },
          {
            title: "Historique",
            to: "cat",
            action: "read",
            subject: "cat",
          },
        ],
      },
    ],
  },
  {
    title: "Ajouter utilisateur",
    to: "user",
    icon: { icon: "tabler-file-plus" },
    action: "read",
    subject: "user",
    children: [
      {
        title: "Utilisateurs",
        to: "user",
        action: "read",
        subject: "user",
      },
      {
        title: "Ajouter",
        to: "user-add",
        action: "create",
        subject: "user",
      },
    ],
  },
  // {
  //   title: "Contrat Hypothécaire",
  //   icon: { icon: "tabler-home-ribbon" },
  //   action: "read",
  //   subject: "mortgage-contract",
  //   children: [
  //     {
  //       title: "Pv Comité",
  //       action: "read",
  //       subject: "pv",
  //       children: [
  //         {
  //           title: "Sans notification",
  //           to: "pv-without-notification",
  //           action: "read",
  //           subject: "pv",
  //         },
  //         {
  //           title: "Historique",
  //           to: { name: "pv-historical-mortgage" },
  //           action: "historical",
  //           subject: "pv",
  //         },
  //       ],
  //     },
  //     {
  //       title: "Notification",
  //       action: "without-signed-contract",
  //       subject: "notification",
  //       children: [
  //         {
  //           title: "Ajouter",
  //           to: "notification-add",
  //           action: "create",
  //           subject: "notification",
  //         },
  //         {
  //           title: "Sans validation head",
  //           to: "notification",
  //           action: "read",
  //           subject: "notification",
  //         },
  //         {
  //           title: "Sans contrat notarié",
  //           to: "notification-without-signed-contract",
  //           action: "without-signed-contract",
  //           subject: "notification",
  //         },
  //         {
  //           title: "Historique",
  //           to: "notification-historical",
  //           action: "historical",
  //           subject: "notification",
  //         },
  //       ],
  //     },
  //     {
  //       title: "CAT",
  //       subject: "cat",
  //       action: "read",
  //       children: [
  //         {
  //           title: "Ajouter",
  //           to: "cat-notification-add",
  //           action: "create",
  //           subject: "cat",
  //         },
  //         {
  //           title: "Historique",
  //           to: "cat-notification",
  //           action: "read",
  //           subject: "cat",
  //         },
  //       ],
  //     },
  //   ],
  // },

  // {
  //   title: "Notification Simplifiée",
  //   icon: { icon: "tabler-bell-ringing" },
  //   action: "simple-notification",
  //   subject: "simple-notification",
  //   children: [
  //     {
  //       title: "Notification",
  //       action: "without-signed-notification",
  //       subject: "simple-notification",
  //       children: [
  //         {
  //           title: "Ajouter",
  //           to: "simple-notification-add",
  //           action: "create",
  //           subject: "simple-notification",
  //         },
  //         {
  //           title: "Sans validation head",
  //           to: "simple-notification",
  //           action: "read",
  //           subject: "simple-notification",
  //         },
  //         {
  //           title: "Sans notification signé",
  //           to: "simple-notification-without-signed-notification",
  //           action: "without-signed-notification",
  //           subject: "simple-notification",
  //         },
  //         {
  //           title: "Historique",
  //           to: "simple-notification-historical",
  //           action: "historical",
  //           subject: "simple-notification",
  //         },
  //       ],
  //     },
  //     {
  //       title: "CAT",
  //       subject: "cat",
  //       action: "read",
  //       children: [
  //         {
  //           title: "Ajouter",
  //           to: "cat-simple-notification-add",
  //           action: "create",
  //           subject: "cat",
  //         },
  //         {
  //           title: "Historique",
  //           to: "cat-simple-notification",
  //           action: "read",
  //           subject: "cat",
  //         },
  //       ],
  //     },
  //   ],
  // },
  // {
  //   title: "Report d'échéance",
  //   icon: { icon: "tabler-calendar-repeat" },
  //   subject: "deadline-postponed",
  //   action: "read",
  //   children: [
  //     {
  //       title: "Ajouter",
  //       // to: 'deadline-postponed',
  //       action: "create",
  //       subject: "deadline-postponed",
  //     },
  //     {
  //       title: "En attente",
  //       to: "deadline-postponed",
  //       action: "read",
  //       subject: "deadline-postponed",
  //     },
  //     {
  //       title: "Historique",
  //       // to: 'deadline-postponed-historical',
  //       action: "historical",
  //       subject: "deadline-postponed",
  //     },
  //   ],
  // },
  // {
  //   title: "Remboursement anticipé",
  // },
  // {
  //   title: "Lettre de mise en demeure",
  // },
  // {
  //   title: "Checking post-deblocage",
  // },
];
