export type ClientColumn = {
	cli_id: number;
	cli_nom: string;
	cli_ape: string;
	cli_tel: string;
	cli_ema: string;
	cli_dir: string;
	cli_sex: string;
	updated_at: string;
	created_at: string;
};

export type ProductTypeColumn = {
	tip_pro_id: number;
	tip_pro_nom: string;
};

export type ProductStatusColumn = {
	est_pro_id: number;
	est_pro_nom: string;
};

export type ProductColumn = {
	pro_id: number;
	pro_nom: string;
	pro_val: number;
	fk_tip_pro_id: number;
	fk_est_pro_id: number;
	tipo_producto?: ProductTypeColumn;
	estado_producto?: ProductStatusColumn;
};

export type InventoryStatusColumn = {
	est_inv_id: number;
	est_inv_des: string;
};

export type InventoryColumn = {
	inv_id: number;
	fk_pro_id: number;
	inv_stock: number;
	fk_est_inv_id: number;
	producto: ProductColumn;
	estado_inventario: InventoryStatusColumn;
};

export type InventoryLogTypeColumn = {
  tip_reg_inv_id: number;
  tip_reg_inv_des: string;
};

export type InventoryLogColumn = {
  reg_inv_id: number;
  fk_inv_id: number;
  reg_inv_fec: string;
  reg_inv_can: number;
  fk_reg_inv_tip: number;
  inventario: InventoryColumn;
  tipo_registro: InventoryLogTypeColumn;
}

export type OrderStatusColumn = {
	est_ped_id: number;
	est_ped_nom: string;
};

export type OrderColumn = {
	ped_id: number;
	ped_est: string;
	ped_fec: string;
	ped_tot: number;
	fk_est_ped_id: number;
	created_at: string;
	updated_at: string;
	cliente: ClientColumn;
	estado_pedido: OrderStatusColumn;
	detalle_pedido: OrderDetailColumn[];
};

export type OrderDetailColumn = {
	det_ped_id: number;
	fk_ped_id: number;
	fk_pro_id: number;
	det_ped_can: number;
	det_ped_pre: number;
	producto: ProductColumn;
};

export type SalesColumn = {
	ven_id: number;
	fk_cli_id: number;
  fk_ped_id: number;
	ven_tot: number;
	created_at: string;
  updated_at: string;
};

export type OrderProduct = {
	fk_est_inv_id: number;
	fk_pro_id: number;
	inv_stock: number;
	inv_id: number;
	producto: ProductColumn;
};
