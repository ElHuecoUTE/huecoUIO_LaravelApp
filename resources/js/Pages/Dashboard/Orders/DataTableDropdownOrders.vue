<script setup lang="ts">
import AlertDialogItem from '@/components/table/AlertDialogItem.vue';
import { OrderColumn } from '@/components/table/columns';
import DrawerItem from '@/components/table/DrawerItem.vue';
import { Button } from '@/components/ui/button';
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuLabel,
	DropdownMenuSeparator,
	DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { toast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import {
	CalendarCog,
	CalendarDays,
	Check,
	DollarSign,
	Map,
	MoreHorizontal,
	Phone
} from 'lucide-vue-next';

defineProps<{
	original: OrderColumn;
	dataRef: any;
}>();

const updateStateForm = useForm({
	id: -1,
  deleteOrder: false,
});

const updateState = (deleteOrder: boolean = false) => {
	if (updateStateForm.id === -1) {
		toast({
			title: 'Error',
			description: 'No se ha seleccionado un pedido para actualizar',
			variant: 'destructive'
		});
		return;
	}

  if (deleteOrder) {
    updateStateForm.deleteOrder = true;
  }

	updateStateForm.put(route('pedidos.update', {}), {
		preserveScroll: true,
		onSuccess: () => {
			toast({
				title: 'Pedido actualizado',
				description: deleteOrder ? 'El pedido ha sido cancelado' : 'El pedido ha sido actualizado',
			});
      updateStateForm.deleteOrder = false;
		},
		onError: () => {
			toast({
				title: 'Error',
				description: 'Ha ocurrido un error al actualizar el pedido',
				variant: 'destructive'
			});
      updateStateForm.deleteOrder = false;
		}
	});
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
			<!-- Ver inventario -->
			<DrawerItem
				dropdownText="Ver cliente"
				:title="original.cliente.cli_nom + ' ' + original.cliente.cli_ape"
				:description="original.cliente.cli_ema"
			>
				<div class="relative w-full p-4 pb-0">
					<div class="grid w-full grid-cols-2 gap-2">
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<Phone />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Telefono</p>
								<p class="text-sm text-muted-foreground">
									{{ original.cliente.cli_tel }}
								</p>
							</div>
						</div>
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<CalendarDays />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Sexo</p>
								<p class="text-sm text-muted-foreground">
									{{
										original.cliente.cli_sex === 'o'
											? 'Otro'
											: original.cliente.cli_sex === 'm'
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
									{{ original.cliente.cli_dir }}
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

			<DrawerItem
				dropdownText="Ver pedido"
				:title="`Pedido de ${original.cliente.cli_nom + ' ' + original.cliente.cli_ape}`"
				:description="original.cliente.cli_ema"
			>
				<div class="relative w-full p-4 pb-0">
					<div class="grid w-full grid-cols-2 gap-2">
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<Check />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Estado</p>
								<p class="text-sm capitalize text-muted-foreground">
									{{ original.estado_pedido.est_ped_nom }}
								</p>
							</div>
						</div>
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<DollarSign />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Total</p>
								<p class="text-sm text-muted-foreground">${{ original.ped_tot }}</p>
							</div>
						</div>
						<div class="col-span-2 flex w-full items-center space-x-4 rounded-md border p-4">
							<CalendarCog />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Fecha de modificacion</p>
								<p class="text-sm text-muted-foreground">
									{{ new Date(original.updated_at).toLocaleDateString() }}
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

			<DrawerItem
				v-if="original.detalle_pedido.length > 0"
				dropdownText="Ver detalles"
				title="Detalles del pedido"
				:description="`Cantidad de productos: ${original.detalle_pedido.length}`"
			>
				<div class="relative w-full p-4 pb-0">
					<div class="grid w-full grid-cols-2 gap-2">
						<div
							v-for="detalle in original.detalle_pedido"
							:key="detalle.fk_pro_id"
							class="flex w-full items-center space-x-4 rounded-md border p-4 last-of-type:odd:col-span-2"
						>
							<span class="text-xl font-semibold"> {{ detalle.det_ped_can }}x </span>
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">
									{{ detalle.producto.pro_nom }}
								</p>
								<p class="text-sm capitalize text-muted-foreground">
									{{ detalle.producto.tipo_producto!.tip_pro_nom }}
								</p>
							</div>
						</div>
					</div>
				</div>
			</DrawerItem>

			<DropdownMenuSeparator v-if="original.fk_est_ped_id === 1 || original.fk_est_ped_id === 2" />
			<!-- Agregar inv -->
			<AlertDialogItem
				v-if="original.fk_est_ped_id === 2"
				dropdownText="Actualizar pedido"
				title="Actualizar pedido"
				description="¿Estás seguro de que deseas actualizar este pedido? No se podrá volver atrás."
				cancel="Cancelar"
				action="Avanzar"
				@submit="updateState"
				@opened="() => (updateStateForm.id = original.ped_id)"
				@closed="() => (updateStateForm.id = -1)"
			>
			</AlertDialogItem>
			<AlertDialogItem
        v-if="original.fk_est_ped_id === 1"
				dropdownText="Cancelar pedido"
				title="Cancelar pedido"
				description="¿Estás seguro de que deseas cancelar este pedido? No se podrá volver atrás."
				cancel="Cancelar"
				action="Confirmar"
				@submit="() => updateState(true)"
				@opened="() => (updateStateForm.id = original.ped_id)"
				@closed="() => (updateStateForm.id = -1)"
			>
			</AlertDialogItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
