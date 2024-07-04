<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useToast } from '@/components/ui/toast/use-toast';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
	canResetPassword?: boolean;
	status?: string;
}>();

const form = useForm({
	email: '',
	password: '',
	remember: false
});
const { toast } = useToast();

const submit = () => {
	form.post(route('login'), {
		onFinish: () => {
			form.reset('password');
		},
		onError: () => {
			toast({
				title: 'Uh oh! Algo salió mal.',
				description: 'Por favor, revisa tus credenciales e intenta de nuevo.',
				variant: 'destructive',
				duration: 5000
			});
		}
	});
};
</script>

<template>
	<GuestLayout>
		<Head title="Log in" />
		<form @submit.prevent="submit" class="flex flex-col items-center justify-center gap-y-4">
			<div class="grid w-full max-w-sm items-center gap-1.5">
				<Label for="email">Email</Label>
				<Input
					id="email"
					type="email"
					placeholder="Email"
					v-model="form.email"
					required
					autofocus
					autocomplete="username"
				/>
			</div>
			<div class="grid w-full max-w-sm items-center gap-1.5">
				<Label for="password">Contraseña</Label>
				<Input
					id="email"
					type="password"
					placeholder="Contraseña"
					v-model="form.password"
					required
					autocomplete="current-password"
				/>
			</div>
			<div class="relative flex w-full items-center justify-between px-2">
				<div class="flex items-center space-x-2">
					<Checkbox id="remember" v-model:checked="form.remember" />
					<label
						for="remember"
						class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
					>
						Recuerdame
					</label>
				</div>
				<Button type="submit" class="w-full max-w-32"> Iniciar sesión </Button>
			</div>
			<Link
				:href="route('register')"
				class="mt-4 rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
			>
				Aun no tienes cuenta?
			</Link>
		</form>
	</GuestLayout>
</template>
