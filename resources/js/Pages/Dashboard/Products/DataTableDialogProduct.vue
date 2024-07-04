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
			valor: number;
			tipo: string | null;
		}>
	>;
	types: Array<{ tip_pro_id: number; tip_pro_nom: string }>;
}>();

const submit = () => {
	props.form.post(route('productos.store'), {
		onSuccess: () => {
			toast({
				title: 'Producto creado',
				description: 'El producto ha sido creado exitosamente.',
				duration: 5000
			});
			props.form.reset();
		},
		onError: (errors: any) => {
			toast({
				title: 'Error al crear el producto',
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
			<Button> Crear Producto </Button>
		</DialogTrigger>
		<DialogContent class="sm:max-w-[425px]">
			<form @submit.prevent="submit">
				<DialogHeader>
					<DialogTitle> Crear nuevo producto </DialogTitle>
					<DialogDescription> Complete los campos para crear un nuevo producto. </DialogDescription>
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
						<Label for="valor" class="text-right"> Valor </Label>
						<Input
							id="valor"
							class="col-span-3"
							required
							type="number"
							v-model="form.valor"
							:disabled="form.processing"
							step="0.01"
						/>
					</div>
					<div
						class="grid grid-cols-4 items-center gap-4"
						v-if="typeof form.tipo === 'string' || form.tipo === null"
					>
						<Label for="tipo" class="col-span-1 text-right"> Tipo </Label>
						<Select v-model="form.tipo" :disabled="form.processing">
							<SelectTrigger class="col-span-3">
								<SelectValue placeholder="Seleccione un tipo de producto" />
							</SelectTrigger>
							<SelectContent>
								<SelectGroup>
									<SelectItem
										v-for="type in types"
										:key="type.tip_pro_id"
										:value="type.tip_pro_id.toString()"
									>
										{{ type.tip_pro_nom }}
									</SelectItem>
								</SelectGroup>
							</SelectContent>
						</Select>
					</div>
				</div>
				<DialogFooter>
					<Button type="submit" :disabled="form.processing">
						<Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
						Crear producto
					</Button>
				</DialogFooter>
			</form>
		</DialogContent>
	</Dialog>
</template>
