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
import { toast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

const form = useForm({
	nombre: ''
});
const submit = () => {
	form.post(route('tipos-productos.store'), {
		preserveScroll: true,
		onSuccess: () => {
			form.reset();
			toast({
				title: 'Tipo de producto creado',
				description: 'El tipo de producto ha sido creado exitosamente.',
				duration: 5000
			});
		},
		onError: () => {
			toast({
				title: 'Error al crear el tipo de producto',
				description: 'Ha ocurrido un error al intentar crear el tipo de producto.',
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
			<Button> Crear Tipo </Button>
		</DialogTrigger>
		<DialogContent class="sm:max-w-[425px]">
			<form @submit.prevent="submit">
				<DialogHeader>
					<DialogTitle> Crear nuevo tipo </DialogTitle>
					<DialogDescription>
						Complete los campos para crear un nuevo tipo de producto.
					</DialogDescription>
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
				</div>
				<DialogFooter>
					<Button type="submit" :disabled="form.processing">
						<Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
						Crear tipo
					</Button>
				</DialogFooter>
			</form>
		</DialogContent>
	</Dialog>
</template>
