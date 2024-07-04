<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
	Select,
	SelectContent,
	SelectGroup,
	SelectItem,
	SelectTrigger,
	SelectValue
} from '@/components/ui/select';
import { toast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

const props = defineProps<{
	form: ReturnType<
		typeof useForm<{
			nombre: string;
			apellido: string;
			email: string;
			telefono: string;
			direccion: string;
			sexo: string;
		}>
	>;
}>();

const submit = () => {
	props.form.post(route('clientes.store'), {
		onSuccess: () => {
			props.form.reset();
			toast({
				title: 'Cliente creado',
				description: 'El cliente ha sido creado exitosamente.',
				duration: 5000
			});
		},
		onError: (errors: any) => {
			toast({
				title: 'Error al crear el cliente',
				description:
					(Object.values(errors)[0] as string) ||
					'Por favor, revise los campos e intente de nuevo.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};
</script>

<template>
	<Dialog>
		<DialogTrigger>
			<Button> Crear Cliente </Button>
		</DialogTrigger>
		<DialogContent class="sm:max-w-[425px]">
			<form @submit.prevent="submit">
				<DialogHeader>
					<DialogTitle> Crear nuevo cliente </DialogTitle>
					<DialogDescription> Complete los campos para crear un nuevo cliente. </DialogDescription>
				</DialogHeader>
				<div class="grid gap-4 py-4">
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="nombre" class="text-right"> Nombre </Label>
						<Input
							id="nombre"
							class="col-span-3"
							required
							v-model="form.nombre"
							:disabled="form.processing"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="apellido" class="text-right"> Apellido </Label>
						<Input
							id="apellido"
							class="col-span-3"
							required
							v-model="form.apellido"
							:disabled="form.processing"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="email" class="text-right"> Email </Label>
						<Input
							id="email"
							class="col-span-3"
							required
							v-model="form.email"
							:disabled="form.processing"
							type="email"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="telefono" class="text-right"> Teléfono </Label>
						<Input
							id="telefono"
							class="col-span-3"
							required
							v-model="form.telefono"
							:disabled="form.processing"
							type="tel"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="direccion" class="text-right"> Dirección </Label>
						<Input
							id="direccion"
							class="col-span-3"
							required
							v-model="form.direccion"
							:disabled="form.processing"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="sexo" class="col-span-1 text-right"> Sexo </Label>
						<Select v-model="form.sexo" :disabled="form.processing">
							<SelectTrigger class="col-span-3">
								<SelectValue placeholder="Seleccione el sexo" />
							</SelectTrigger>
							<SelectContent>
								<SelectGroup>
									<SelectItem value="m"> Hombre </SelectItem>
									<SelectItem value="f"> Mujer </SelectItem>
									<SelectItem value="o"> Otro </SelectItem>
								</SelectGroup>
							</SelectContent>
						</Select>
					</div>
				</div>
				<DialogFooter>
					<Button type="submit" :disabled="form.processing">
						<Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
						Crear cliente
					</Button>
				</DialogFooter>
			</form>
		</DialogContent>
	</Dialog>
</template>
