mv .env .env-tmp;
mv .env-production .env;
pnpm build;
zip -r credit.zip app bootstrap config database document_templates lang public resources routes storage tests .scribe themeConfig.js vite.config.js jsconfig.json package.json .eslintrc-auto-import.json .stylelintrc.json artisan phpunit.xml docker-compose.yml pnpm-lock.yaml README.md .editorconfig .env.example .eslintrc.cjs .gitattributes .gitignore .npmrc .nvmrc auto-imports.d.ts components.d.ts typed-router.d.ts;
cp credit.zip /var/www/html;
mv .env .env-production;
mv .env-tmp .env;
rm credit.zip
