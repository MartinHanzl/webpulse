<script setup lang="ts">
const { t } = useI18n();
</script>

<template>
  <div>
    <BasePropsHeading type="h2" class="mb-6 text-center">
      {{ t("home.technologiesTitle") }}
    </BasePropsHeading>
    <div class="flex justify-center gap-x-6">
      <NuxtImg
        src="/static/img/icon/technologies/laravel.svg"
        alt="Laravel"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/docker.svg"
        alt="Docker"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/aws.svg"
        alt="Amazon web services"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/nodejs.svg"
        alt="Node.js"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/npm.svg"
        alt="Npm"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/php.svg"
        alt="PHP"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/nuxt.svg"
        alt="Nuxt.js"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/js.svg"
        alt="JavaScript"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/github.svg"
        alt="Github"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/figma.svg"
        alt="Figma"
        class="w-[50%] h-auto"
      />
      <NuxtImg
        src="/static/img/icon/technologies/mysql.svg"
        alt="Mysql"
        class="w-[50%] h-auto"
      />
    </div>
  </div>
</template>
