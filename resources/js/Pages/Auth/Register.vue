<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
	name: '',
	email: '',
	password: '',
	password_confirmation: ''
});

const submit = () => {
	form.post(route('register'), {
		onFinish: () => {
			form.reset('password', 'password_confirmation');
		}
	});
};
</script>

<template>
	<GuestLayout>
		<Head title="Register" />

		<form @submit.prevent="submit" class="flex flex-col gap-2">
			<div>
				<Label for="name" value="Name"> Nombre </Label>

				<Input
					id="name"
					type="text"
					class="w-full"
					v-model="form.name"
					required
					autofocus
					autocomplete="name"
				/>
			</div>

			<div>
				<Label for="email" value="Email"> Email </Label>

				<Input
					id="email"
					type="email"
					class="w-full"
					v-model="form.email"
					required
					autocomplete="username"
				/>
			</div>

			<div>
				<Label for="password" value="Password"> Contraseña </Label>

				<Input
					id="password"
					type="password"
					class="w-full"
					v-model="form.password"
					required
					autocomplete="new-password"
				/>
			</div>

			<div>
				<Label for="password_confirmation" value="Confirm Password"> Confirmar contraseña </Label>

				<Input
					id="password_confirmation"
					type="password"
					class="w-full"
					v-model="form.password_confirmation"
					required
					autocomplete="new-password"
				/>
			</div>

			<div class="mt-2 flex items-center justify-end">
				<Link
					:href="route('login')"
					class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
				>
					Registrado?
				</Link>

				<Button class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
					Registrar
				</Button>
			</div>
		</form>
	</GuestLayout>
</template>
