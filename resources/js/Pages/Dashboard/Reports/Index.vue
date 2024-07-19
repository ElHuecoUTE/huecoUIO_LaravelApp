<script setup lang="ts">
import { InventoryLogColumn} from '@/components/table/columns';
import DataTable from '@/components/table/DataTable.vue';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InfoCard from '@/components/InfoCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import { onMounted, ref, h, watch } from 'vue';

const props = defineProps<{
	stats: {
		clients: any[];
		orders: any[];
    invLog: any[];
		products: number;
		productTypes: number;
	};
  allInvLog: any[];
}>();

type CustomColumnDef =
  | ColumnDef<InventoryLogColumn>
  | {
      name: string;
    };
const LOGS_INVENTORY_COLUMNS: CustomColumnDef[]=[
  {
    id: 'reg_inv_id',
    accessorKey: 'reg_inv_id',
    header: ({ column }) => {
      return h(
        Button,
        {
          variant: 'ghost',
          onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        },
        () => ['ID', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
      );
    },
    cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.reg_inv_id]),
    enableSorting: true,
    name: 'ID'
  },
  {
    accessorKey: 'reg_inv_fec',
    header: ({ column }) => {
      return h(
        Button,
        {
          variant: 'ghost',
          onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        },
        () => ['Fecha', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
      );
    },
    cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.reg_inv_fec]),
    enableSorting: true,
    name: 'Fecha'
  },
  {
    accessorKey: 'pro_nom',
    header: ({ column }) => {
      return h(
        Button,
        {
          variant: 'ghost',
          onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        },
        () => ['Descripcion', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
      );
    },
    cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.inventario.producto.pro_nom]),
    filterFn: (row, _, filterValue) => {
      return row.original.inventario.producto.pro_nom.toLowerCase().includes(filterValue.toLowerCase());
    },
    enableSorting: true,
    name: 'Producto'
  },
  {
    accessorKey: 'reg_inv_can',
    header: ({ column }) => {
      return h(
        Button,
        {
          variant: 'ghost',
          onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        },
        () => ['Cantidad', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
      );
    },
    cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.reg_inv_can]),
    enableSorting: true,
    name: 'Cantidad'
  },
  {
    accessorKey: 'fk_reg_inv_tip',
    header: ({ column }) => {
      return h(
        Button,
        {
          variant: 'ghost',
          onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        },
        () => ['Tipo', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
      );
    },
    cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.fk_reg_inv_tip === 1 ? 'Entrada' : 'Salida']),
    enableSorting: true,
    name: 'Tipo'
  }
];

const dataRef = ref(props.allInvLog || []);
const filters: string = 'pro_nom';

watch(
  () => props.allInvLog,
  (value) => {
    if (value) {
      dataRef.value = [...dataRef.value, ...value];
    }
  }
);

const chart = ref<HTMLCanvasElement | null>(null);

onMounted(() => {
	const newestDate = new Date();
	const oldestDate = new Date(newestDate);
	oldestDate.setDate(oldestDate.getDate() - 30);

	const clientCountPerDay = props.stats.clients.reduce((acc, client) => {
		const date = new Date(client.created_at).toLocaleDateString();
		acc[date] = acc[date] ? acc[date] + 1 : 1;
		return acc;
	}, {});
	const orderCountPerDay = props.stats.orders.reduce((acc, order) => {
		const date = new Date(order.ped_fec).toLocaleDateString();
		acc[date] = acc[date] ? acc[date] + 1 : 1;
		return acc;
	}, {});

  const invLog: {
    entered: any[];
    exited: any[];
  } = {
    entered: [],
    exited: []
  };

  for (const order of props.stats.invLog) {
    if (order.fk_reg_inv_tip === 2) {
      invLog.exited.push(order as any);
    } else {
      invLog.entered.push(order);
    }
  }

  const exitedOrderCountPerDay = invLog.exited.reduce((acc, order) => {
    const date = new Date(order.reg_inv_fec).toLocaleDateString();
    acc[date] = acc[date] ? acc[date] + 1 : 1;
    return acc;
  }, {});

  const enteredOrderCountPerDay = invLog.entered.reduce((acc, order) => {
    const date = new Date(order.reg_inv_fec).toLocaleDateString();
    acc[date] = acc[date] ? acc[date] + 1 : 1;
    return acc;
  }, {});

  const last30Days = Array.from({ length: 30 }, (_, i) => {
    const date = new Date(oldestDate);
    date.setDate(date.getDate() + i);
    return date.toLocaleDateString();
  });

  let clientsArray: number[] = [];
  let ordersArray: number[] = [];
  let exitedArray: number[] = [];
  let enteredArray: number[] = [];
  for (const date of last30Days) {
    clientsArray.push(clientCountPerDay[date] || 0);
    ordersArray.push(orderCountPerDay[date] || 0);
    exitedArray.push(exitedOrderCountPerDay[date] || 0);
    enteredArray.push(enteredOrderCountPerDay[date] || 0);
  }

  console.log(orderCountPerDay)

	new Chart(chart.value!, {
		type: 'line',
		data: {
			labels: last30Days,
			datasets: [
				{
					label: 'Clientes',
					data: clientsArray,
					backgroundColor: 'rgba(54, 162, 235, 0.2)',
					borderColor: 'rgba(54, 162, 235, 1)',
					borderWidth: 1,
					tension: 0.1
				},
				{
					label: 'Pedidos',
					data: ordersArray,
					backgroundColor: 'rgba(255, 99, 132, 0.2)',
					borderColor: 'rgba(255, 99, 132, 1)',
					borderWidth: 1,
					tension: 0.1
				},
        {
          label: 'Pedidos salidos',
          data: exitedArray,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
          tension: 0.1
        },
        {
          label: 'Pedidos entrados',
          data: enteredArray,
          backgroundColor: 'rgba(153, 102, 255, 0.2)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1,
          tension: 0.1
        }
			]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				},
        x: {
          display: true,
          title: {
            display: true,
            text: 'Fecha'
          }
        }
			}
		}
	});
});
</script>

<template>
	<Head title="Reportes" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Reportes</h2>
		</template>
		<div class="px-4 py-12">
			<div class="mx-auto flex max-w-7xl flex-col gap-y-4 sm:px-6 lg:px-8">
				<div class="grid grid-cols-3 gap-4">
					<InfoCard
						title="Clientes creados"
						subtitle="Ultimos 30 dias"
						:mainValue="stats.clients.length"
					/>
					<InfoCard
						title="Productos creados"
						subtitle="Ultimos 30 dias"
						:mainValue="stats.products"
					/>
					<InfoCard title="Tipos de productos" subtitle="Total" :mainValue="stats.productTypes" />
				</div>
				<div class="relative mt-8 w-full">
					<canvas ref="chart"></canvas>
				</div>

        <h3 class="text-lg font-semibold leading-tight text-gray-800">Registros de Inventario</h3>
        <DataTable
          :data="dataRef || []"
          :columns="LOGS_INVENTORY_COLUMNS as unknown as ColumnDef<any>[]"
          :filters="filters || ''"
          placeholder="Buscar Registro por Nombre del Producto"
          >
        </DataTable>

			</div>
		</div>
	</AuthenticatedLayout>
</template>
