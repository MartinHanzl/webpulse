<script setup lang="ts">
import { Form } from 'vee-validate';

const toast = useToast();
const error = ref(false);
const loading = ref(false);
const { refreshIdentity } = useSanctumAuth();

const item = ref({
  firstname: '' as string,
  lastname: '' as string,
  avatar: '' as string,
  email: '' as string,
  phone_prefix: '+420' as string,
  phone: '' as string,
  invitation_token: '' as string,
  street: '' as string,
  city: '' as string,
  zip: '' as string,
});

async function submitForm() {
  const client = useSanctumClient();
}
const breadcrumbs = [
	{
		name: 'Profil',
		link: '/profil',
		current: true,
	},
];
</script>

<template>
	<div>
		<LayoutHeader
			title="Profil"
			:breadcrumbs="breadcrumbs"
		/>
    <LayoutContainer>
      <Form @submit="submitForm">
        <div class="grid grid-cols-2 gap-8">
          <BaseFormInput
            v-model="item.firstname"
            label="Jméno"
            name="firstname"
            rules="required"
            class="col-span-1"
          />
          <BaseFormInput
            v-model="item.lastname"
            label="Příjmení"
            name="lastname"
            rules="required"
            class="col-span-1"
          />
          <BaseFormInput
            v-model="item.email"
            label="E-mail"
            name="email"
            rules="required"
            class="col-span-1"
          />
        </div>
      </Form>
    </LayoutContainer>
	</div>
</template>
