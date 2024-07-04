<script setup lang="ts">
import AlertDialogItem from '@/components/table/AlertDialogItem.vue';
import { ClientColumn } from '@/components/table/columns';
import DialogItem from '@/components/table/DialogItem.vue';
import DrawerItem from '@/components/table/DrawerItem.vue';
import { Button } from '@/components/ui/button';
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuLabel,
	DropdownMenuSeparator,
	DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { toast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import { CalendarDays, Map, MoreHorizontal, Phone } from 'lucide-vue-next';

const props = defineProps<{
	original: ClientColumn;
	updateForm: ReturnType<
		typeof useForm<{
			nombre: string;
			apellido: string;
			email: string;
			direccion: string;
			id: number;
		}>
	>;
	dataRef: any;
}>();

const disableForm = useForm({
	id: -1
});

const updateSubmit = async () => {
	if (props.updateForm.id <= -1) {
		toast({
			title: 'Error al actualizar',
			description: 'No se ha seleccionado un cliente para actualizar.',
			duration: 5000,
			variant: 'destructive'
		});
		return;
	}

	props.updateForm.put(route('clientes.update', {}), {
		onSuccess: () => {
			toast({
				title: 'Cliente actualizado',
				description: 'El cliente ha sido actualizado exitosamente.',
				duration: 5000
			});

			const index = props.dataRef.value.findIndex(
				(item: any) => item.cli_id === props.updateForm.id
			);
			props.dataRef.value[index].cli_nom = props.updateForm.nombre;
			props.dataRef.value[index].cli_ape = props.updateForm.apellido;
			props.dataRef.value[index].cli_ema = props.updateForm.email;
			props.dataRef.value[index].cli_dir = props.updateForm.direccion;
		},
		onError: (errors) => {
			toast({
				title: 'Error al actualizar el cliente',
				description: Object.values(errors)[0] || 'Por favor, revise los campos e intente de nuevo.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};

const disableSubmit = async () => {
	if (disableForm.id <= -1) {
		toast({
			title: 'Error al deshabilitar',
			description: 'No se ha seleccionado un cliente para deshabilitar.',
			duration: 5000,
			variant: 'destructive'
		});
		return;
	}

	disableForm.delete(
		route('clientes.destroy', {
			id: disableForm.id
		}),
		{
			onSuccess: () => {
				toast({
					title: 'Cliente deshabilitado',
					description: 'El cliente ha sido deshabilitado exitosamente.',
					duration: 5000
				});

				props.dataRef.value = props.dataRef.value.filter(
					(item: any) => item.cli_id !== disableForm.id
				);
			},
			onError: (errors) => {
				toast({
					title: 'Error al deshabilitar el cliente',
					description:
						Object.values(errors)[0] || 'Por favor, revise los campos e intente de nuevo.',
					duration: 5000,
					variant: 'destructive'
				});
			}
		}
	);
};

const openedUpdateForm = () => {
	props.updateForm.nombre = props.original.cli_nom;
	props.updateForm.apellido = props.original.cli_ape;
	props.updateForm.email = props.original.cli_ema;
	props.updateForm.direccion = props.original.cli_dir;
	props.updateForm.id = props.original.cli_id;
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger as-child>
			<Button variant="ghost" class="h-8 w-8 p-0">
				<span class="sr-only">Abrir menu</span>
				<MoreHorizontal class="h-4 w-4" />
			</Button>
		</DropdownMenuTrigger>
		<DropdownMenuContent align="end">
			<DropdownMenuLabel>Acciones</DropdownMenuLabel>
			<!-- Ver cliente -->
			<DrawerItem
				dropdownText="Ver cliente"
				:title="original.cli_nom + ' ' + original.cli_ape"
				:description="original.cli_ema"
			>
				<div class="relative w-full p-4 pb-0">
					<div class="grid w-full grid-cols-2 gap-2">
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<Phone />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Telefono</p>
								<p class="text-sm text-muted-foreground">
									{{ original.cli_tel }}
								</p>
							</div>
						</div>
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<CalendarDays />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Sexo</p>
								<p class="text-sm text-muted-foreground">
									{{
										original.cli_sex === 'O'
											? 'Otro'
											: original.cli_sex === 'M'
												? 'Masculino'
												: 'Femenino'
									}}
								</p>
							</div>
						</div>
						<div class="col-span-2 flex w-full items-center space-x-4 rounded-md border p-4">
							<Map />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Dirección</p>
								<p class="text-sm text-muted-foreground">
									{{ original.cli_dir }}
								</p>
							</div>
						</div>
						<div class="col-span-2 flex w-full items-center space-x-4 rounded-md border p-4">
							<CalendarDays />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Fecha de creacion</p>
								<p class="text-sm text-muted-foreground">
									{{ new Date(original.created_at).toLocaleDateString() }}
								</p>
							</div>
						</div>
					</div>
				</div>
			</DrawerItem>

			<DropdownMenuSeparator />
			<!-- Editar cliente -->
			<DialogItem
				dropdownText="Editar cliente"
				title="Editar cliente"
				description="Complete los campos para actualizar el cliente."
				action="Guardar"
				:disabled="updateForm.processing"
				@submit="updateSubmit"
				@opened="() => openedUpdateForm()"
			>
				<div class="grid gap-4 py-4">
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="nombre" class="text-right"> Nombre </Label>
						<Input
							id="nombre"
							class="col-span-3"
							required
							v-model="updateForm.nombre"
							:disabled="updateForm.processing"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="apellido" class="text-right"> Apellido </Label>
						<Input
							id="apellido"
							class="col-span-3"
							required
							v-model="updateForm.apellido"
							:disabled="updateForm.processing"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="email" class="text-right"> Email </Label>
						<Input
							id="email"
							class="col-span-3"
							required
							v-model="updateForm.email"
							:disabled="updateForm.processing"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="direccion" class="text-right"> Dirección </Label>
						<Input
							id="direccion"
							class="col-span-3"
							required
							v-model="updateForm.direccion"
							:disabled="updateForm.processing"
						/>
					</div>
				</div>
			</DialogItem>

			<!-- Eliminar cliente -->
			<AlertDialogItem
				dropdownText="Bloquear cliente"
				title="Deshabilitar cliente"
				description="Esta acción no se puede deshacer. Esto deshabilitará el cliente y no podrá ser utilizado en la aplicación."
				cancel="Cancelar"
				action="Deshabilitar"
				@submit="disableSubmit"
				@opened="() => (disableForm.id = props.original.cli_id)"
				@closed="() => (disableForm.id = -1)"
			>
			</AlertDialogItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
