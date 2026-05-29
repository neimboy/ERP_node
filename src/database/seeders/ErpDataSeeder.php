<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ErpDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ==========================================
        // 1. CATEGORÍAS
        // ==========================================
        DB::table('categorias')->insert([
            ['Nombre' => 'Materias Primas',       'created_at' => $now, 'updated_at' => $now],
            ['Nombre' => 'Productos Terminados',   'created_at' => $now, 'updated_at' => $now],
            ['Nombre' => 'Insumos de Oficina',     'created_at' => $now, 'updated_at' => $now],
            ['Nombre' => 'Herramientas',           'created_at' => $now, 'updated_at' => $now],
            ['Nombre' => 'Equipos Electrónicos',   'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 2. PROVEEDORES
        // ==========================================
        DB::table('proveedores')->insert([
            ['RUC' => '20100123456', 'Nombre' => 'Industrias Metálicas SAC',    'Telefono' => '01-3456789', 'created_at' => $now, 'updated_at' => $now],
            ['RUC' => '20200234567', 'Nombre' => 'Distribuidora Norte EIRL',    'Telefono' => '044-223344', 'created_at' => $now, 'updated_at' => $now],
            ['RUC' => '20300345678', 'Nombre' => 'Suministros Tech Peru SA',    'Telefono' => '01-6677889', 'created_at' => $now, 'updated_at' => $now],
            ['RUC' => '20400456789', 'Nombre' => 'Ferretería Central SRL',      'Telefono' => '064-445566', 'created_at' => $now, 'updated_at' => $now],
            ['RUC' => '20500567890', 'Nombre' => 'Químicos Industriales SAC',   'Telefono' => '01-9988776', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 3. ALMACENES
        // ==========================================
        DB::table('almacenes')->insert([
            ['Nombre' => 'Almacén Principal',    'Direccion' => 'Av. Industrial 123, Lima',      'created_at' => $now, 'updated_at' => $now],
            ['Nombre' => 'Almacén Secundario',   'Direccion' => 'Jr. Comercio 456, Lima',        'created_at' => $now, 'updated_at' => $now],
            ['Nombre' => 'Almacén Norte',        'Direccion' => 'Av. Panamericana 789, Trujillo','created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 4. PRODUCTOS
        // ==========================================
        DB::table('productos')->insert([
            ['Codigo' => 'MP-001', 'Nombre' => 'Plancha de Acero 4mm',     'Precio_Compra' => 85.00,  'Precio_Venta' => 120.00, 'Id_Categoria' => 1, 'Id_Proveedor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => 'MP-002', 'Nombre' => 'Tubo PVC 2 pulgadas',      'Precio_Compra' => 12.50,  'Precio_Venta' => 20.00,  'Id_Categoria' => 1, 'Id_Proveedor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => 'MP-003', 'Nombre' => 'Resina Epoxi 1kg',         'Precio_Compra' => 45.00,  'Precio_Venta' => 70.00,  'Id_Categoria' => 1, 'Id_Proveedor' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => 'PT-001', 'Nombre' => 'Estructura Metálica A',    'Precio_Compra' => 320.00, 'Precio_Venta' => 500.00, 'Id_Categoria' => 2, 'Id_Proveedor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => 'PT-002', 'Nombre' => 'Tanque de Almacenamiento', 'Precio_Compra' => 780.00, 'Precio_Venta' => 1200.00,'Id_Categoria' => 2, 'Id_Proveedor' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => 'IO-001', 'Nombre' => 'Papel Bond A4 (resma)',    'Precio_Compra' => 12.00,  'Precio_Venta' => 18.00,  'Id_Categoria' => 3, 'Id_Proveedor' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => 'HE-001', 'Nombre' => 'Taladro Industrial 750W',  'Precio_Compra' => 250.00, 'Precio_Venta' => 380.00, 'Id_Categoria' => 4, 'Id_Proveedor' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => 'EE-001', 'Nombre' => 'Laptop Dell 15"',          'Precio_Compra' => 2800.00,'Precio_Venta' => 3500.00,'Id_Categoria' => 5, 'Id_Proveedor' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 5. INVENTARIO
        // ==========================================
        DB::table('inventario')->insert([
            ['Id_Producto' => 1, 'Id_Almacen' => 1, 'Cantidad' => 150, 'Stock_Minimo' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 2, 'Id_Almacen' => 1, 'Cantidad' => 300, 'Stock_Minimo' => 50, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 3, 'Id_Almacen' => 1, 'Cantidad' => 80,  'Stock_Minimo' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 4, 'Id_Almacen' => 2, 'Cantidad' => 25,  'Stock_Minimo' => 5,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 5, 'Id_Almacen' => 2, 'Cantidad' => 12,  'Stock_Minimo' => 3,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 6, 'Id_Almacen' => 3, 'Cantidad' => 200, 'Stock_Minimo' => 30, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 7, 'Id_Almacen' => 1, 'Cantidad' => 18,  'Stock_Minimo' => 5,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 8, 'Id_Almacen' => 3, 'Cantidad' => 10,  'Stock_Minimo' => 2,  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 6. CLIENTES
        // ==========================================
        DB::table('clientes')->insert([
            ['Documento' => '20601234567', 'Nombre' => 'Constructora Andina SAC',     'Correo' => 'contacto@andina.pe',    'Telefono' => '01-2223344', 'created_at' => $now, 'updated_at' => $now],
            ['Documento' => '20702345678', 'Nombre' => 'Minera Horizonte EIRL',       'Correo' => 'compras@horizonte.pe',  'Telefono' => '044-556677', 'created_at' => $now, 'updated_at' => $now],
            ['Documento' => '10345678901', 'Nombre' => 'Juan Carlos Ríos Pérez',      'Correo' => 'jrios@gmail.com',       'Telefono' => '987654321',  'created_at' => $now, 'updated_at' => $now],
            ['Documento' => '20803456789', 'Nombre' => 'Grupo Industrial Lima SA',    'Correo' => 'info@grupilima.com',    'Telefono' => '01-8899001', 'created_at' => $now, 'updated_at' => $now],
            ['Documento' => '10456789012', 'Nombre' => 'María Elena Torres Soto',     'Correo' => 'metorres@hotmail.com',  'Telefono' => '976543210',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 7. PUESTOS
        // ==========================================
        DB::table('puestos')->insert([
            ['Nombre_Puesto' => 'Gerente General',       'Salario_Base' => 8000.00, 'created_at' => $now, 'updated_at' => $now],
            ['Nombre_Puesto' => 'Contador Senior',       'Salario_Base' => 4500.00, 'created_at' => $now, 'updated_at' => $now],
            ['Nombre_Puesto' => 'Vendedor',              'Salario_Base' => 2500.00, 'created_at' => $now, 'updated_at' => $now],
            ['Nombre_Puesto' => 'Almacenero',            'Salario_Base' => 1800.00, 'created_at' => $now, 'updated_at' => $now],
            ['Nombre_Puesto' => 'Operario de Planta',    'Salario_Base' => 1600.00, 'created_at' => $now, 'updated_at' => $now],
            ['Nombre_Puesto' => 'Analista de RRHH',      'Salario_Base' => 3000.00, 'created_at' => $now, 'updated_at' => $now],
            ['Nombre_Puesto' => 'Ingeniero de Proyecto', 'Salario_Base' => 5000.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 8. EMPLEADOS
        // ==========================================
        DB::table('empleados')->insert([
            ['DNI' => '45678901', 'Nombre' => 'Carlos Alberto Mendoza',  'Correo' => 'cmendoza@erp.com',  'Telefono' => '987001001', 'Fecha_Ingreso' => '2020-03-01', 'Estado' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['DNI' => '45678902', 'Nombre' => 'Lucía Fernández Torres',  'Correo' => 'lfernandez@erp.com','Telefono' => '987001002', 'Fecha_Ingreso' => '2019-07-15', 'Estado' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['DNI' => '45678903', 'Nombre' => 'Roberto Sánchez Huanca',  'Correo' => 'rsanchez@erp.com',  'Telefono' => '987001003', 'Fecha_Ingreso' => '2021-01-10', 'Estado' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['DNI' => '45678904', 'Nombre' => 'Ana María Quispe López',  'Correo' => 'aquispe@erp.com',   'Telefono' => '987001004', 'Fecha_Ingreso' => '2022-05-20', 'Estado' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['DNI' => '45678905', 'Nombre' => 'Miguel Ángel Vargas',     'Correo' => 'mvargas@erp.com',   'Telefono' => '987001005', 'Fecha_Ingreso' => '2021-09-01', 'Estado' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['DNI' => '45678906', 'Nombre' => 'Patricia Ramos Castro',   'Correo' => 'pramos@erp.com',    'Telefono' => '987001006', 'Fecha_Ingreso' => '2020-11-15', 'Estado' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['DNI' => '45678907', 'Nombre' => 'Jorge Luis Díaz Poma',    'Correo' => 'jdiaz@erp.com',     'Telefono' => '987001007', 'Fecha_Ingreso' => '2023-02-01', 'Estado' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 9. CONTRATOS
        // ==========================================
        DB::table('contratos')->insert([
            ['Id_Empleado' => 1, 'Id_Puesto' => 1, 'Fecha_Inicio' => '2020-03-01', 'Fecha_Fin' => null,         'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 2, 'Id_Puesto' => 2, 'Fecha_Inicio' => '2019-07-15', 'Fecha_Fin' => null,         'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 3, 'Id_Puesto' => 3, 'Fecha_Inicio' => '2021-01-10', 'Fecha_Fin' => null,         'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 4, 'Id_Puesto' => 4, 'Fecha_Inicio' => '2022-05-20', 'Fecha_Fin' => null,         'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 5, 'Id_Puesto' => 5, 'Fecha_Inicio' => '2021-09-01', 'Fecha_Fin' => '2024-08-31', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 6, 'Id_Puesto' => 6, 'Fecha_Inicio' => '2020-11-15', 'Fecha_Fin' => null,         'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 7, 'Id_Puesto' => 7, 'Fecha_Inicio' => '2023-02-01', 'Fecha_Fin' => null,         'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 10. PERIODOS
        // ==========================================
        DB::table('periodos')->insert([
            ['Año' => 2024, 'Mes' => 10, 'Estado' => 'cerrado',  'created_at' => $now, 'updated_at' => $now],
            ['Año' => 2024, 'Mes' => 11, 'Estado' => 'cerrado',  'created_at' => $now, 'updated_at' => $now],
            ['Año' => 2024, 'Mes' => 12, 'Estado' => 'cerrado',  'created_at' => $now, 'updated_at' => $now],
            ['Año' => 2025, 'Mes' => 1,  'Estado' => 'cerrado',  'created_at' => $now, 'updated_at' => $now],
            ['Año' => 2025, 'Mes' => 2,  'Estado' => 'cerrado',  'created_at' => $now, 'updated_at' => $now],
            ['Año' => 2025, 'Mes' => 3,  'Estado' => 'abierto',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 11. NÓMINAS
        // ==========================================
        DB::table('nominas')->insert([
            // Período 4 (Ene 2025)
            ['Id_Empleado' => 1, 'Id_Periodo' => 4, 'Total_Bruto' => 8000.00, 'Total_Deducciones' => 880.00, 'Neto_Pagar' => 7120.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 2, 'Id_Periodo' => 4, 'Total_Bruto' => 4500.00, 'Total_Deducciones' => 495.00, 'Neto_Pagar' => 4005.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 3, 'Id_Periodo' => 4, 'Total_Bruto' => 2500.00, 'Total_Deducciones' => 275.00, 'Neto_Pagar' => 2225.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 6, 'Id_Periodo' => 4, 'Total_Bruto' => 3000.00, 'Total_Deducciones' => 330.00, 'Neto_Pagar' => 2670.00, 'created_at' => $now, 'updated_at' => $now],
            // Período 5 (Feb 2025)
            ['Id_Empleado' => 1, 'Id_Periodo' => 5, 'Total_Bruto' => 8000.00, 'Total_Deducciones' => 880.00, 'Neto_Pagar' => 7120.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 2, 'Id_Periodo' => 5, 'Total_Bruto' => 4500.00, 'Total_Deducciones' => 495.00, 'Neto_Pagar' => 4005.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 7, 'Id_Periodo' => 5, 'Total_Bruto' => 5000.00, 'Total_Deducciones' => 550.00, 'Neto_Pagar' => 4450.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 12. CUENTAS CONTABLES
        // ==========================================
        DB::table('cuenta_contable')->insert([
            ['Codigo' => '1011', 'Nombre_Cuenta' => 'Caja',                          'Tipo' => 'Activo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '1041', 'Nombre_Cuenta' => 'Cuentas Corrientes',            'Tipo' => 'Activo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '1211', 'Nombre_Cuenta' => 'Facturas por Cobrar',           'Tipo' => 'Activo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '2011', 'Nombre_Cuenta' => 'Mercaderías',                   'Tipo' => 'Activo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '3311', 'Nombre_Cuenta' => 'Maquinaria y Equipo',           'Tipo' => 'Activo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '4011', 'Nombre_Cuenta' => 'Tributos por Pagar (IGV)',      'Tipo' => 'Pasivo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '4111', 'Nombre_Cuenta' => 'Remuneraciones por Pagar',     'Tipo' => 'Pasivo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '4211', 'Nombre_Cuenta' => 'Facturas por Pagar',            'Tipo' => 'Pasivo',  'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '5011', 'Nombre_Cuenta' => 'Capital Social',               'Tipo' => 'Patrimonio','created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '7011', 'Nombre_Cuenta' => 'Ventas de Mercaderías',        'Tipo' => 'Ingreso', 'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '6011', 'Nombre_Cuenta' => 'Compra de Mercaderías',        'Tipo' => 'Gasto',   'created_at' => $now, 'updated_at' => $now],
            ['Codigo' => '6211', 'Nombre_Cuenta' => 'Sueldos y Salarios',           'Tipo' => 'Gasto',   'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 13. ASIENTOS CONTABLES
        // ==========================================
        DB::table('asientos')->insert([
            ['Id_Periodo' => 4, 'Fecha' => '2025-01-05', 'Glosa' => 'Venta de mercaderías al cliente Constructora Andina', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Periodo' => 4, 'Fecha' => '2025-01-15', 'Glosa' => 'Pago de planilla enero 2025',                         'created_at' => $now, 'updated_at' => $now],
            ['Id_Periodo' => 4, 'Fecha' => '2025-01-20', 'Glosa' => 'Compra de materias primas a Industrias Metálicas',    'created_at' => $now, 'updated_at' => $now],
            ['Id_Periodo' => 5, 'Fecha' => '2025-02-03', 'Glosa' => 'Venta de productos a Minera Horizonte',               'created_at' => $now, 'updated_at' => $now],
            ['Id_Periodo' => 5, 'Fecha' => '2025-02-28', 'Glosa' => 'Pago de planilla febrero 2025',                       'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 14. DETALLE DE ASIENTOS
        // ==========================================
        DB::table('asiento_detalle')->insert([
            // Asiento 1: Venta (Cuentas por Cobrar Debe / Ventas Haber)
            ['Id_Asiento' => 1, 'Id_Cuenta' => 3,  'Debe' => 5900.00, 'Haber' => 0.00,    'created_at' => $now, 'updated_at' => $now],
            ['Id_Asiento' => 1, 'Id_Cuenta' => 10, 'Debe' => 0.00,    'Haber' => 5000.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Asiento' => 1, 'Id_Cuenta' => 6,  'Debe' => 0.00,    'Haber' => 900.00,  'created_at' => $now, 'updated_at' => $now],
            // Asiento 2: Planilla (Sueldos Debe / Remuneraciones por Pagar Haber)
            ['Id_Asiento' => 2, 'Id_Cuenta' => 12, 'Debe' => 18000.00,'Haber' => 0.00,    'created_at' => $now, 'updated_at' => $now],
            ['Id_Asiento' => 2, 'Id_Cuenta' => 7,  'Debe' => 0.00,    'Haber' => 18000.00,'created_at' => $now, 'updated_at' => $now],
            // Asiento 3: Compra (Mercaderías Debe / Facturas por Pagar Haber)
            ['Id_Asiento' => 3, 'Id_Cuenta' => 4,  'Debe' => 12750.00,'Haber' => 0.00,    'created_at' => $now, 'updated_at' => $now],
            ['Id_Asiento' => 3, 'Id_Cuenta' => 8,  'Debe' => 0.00,    'Haber' => 12750.00,'created_at' => $now, 'updated_at' => $now],
            // Asiento 4: Venta Feb
            ['Id_Asiento' => 4, 'Id_Cuenta' => 3,  'Debe' => 14160.00,'Haber' => 0.00,    'created_at' => $now, 'updated_at' => $now],
            ['Id_Asiento' => 4, 'Id_Cuenta' => 10, 'Debe' => 0.00,    'Haber' => 12000.00,'created_at' => $now, 'updated_at' => $now],
            ['Id_Asiento' => 4, 'Id_Cuenta' => 6,  'Debe' => 0.00,    'Haber' => 2160.00, 'created_at' => $now, 'updated_at' => $now],
            // Asiento 5: Planilla Feb
            ['Id_Asiento' => 5, 'Id_Cuenta' => 12, 'Debe' => 17500.00,'Haber' => 0.00,    'created_at' => $now, 'updated_at' => $now],
            ['Id_Asiento' => 5, 'Id_Cuenta' => 7,  'Debe' => 0.00,    'Haber' => 17500.00,'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 15. ÓRDENES DE VENTA
        // ==========================================
        DB::table('ordenes')->insert([
            ['Id_Cliente' => 1, 'Fecha' => '2025-01-05 09:00:00', 'Estado' => 'completado', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 2, 'Fecha' => '2025-01-18 10:30:00', 'Estado' => 'completado', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 3, 'Fecha' => '2025-02-03 08:00:00', 'Estado' => 'completado', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 4, 'Fecha' => '2025-02-20 14:00:00', 'Estado' => 'pendiente',  'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 1, 'Fecha' => '2025-03-01 11:00:00', 'Estado' => 'pendiente',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 16. DETALLE DE ÓRDENES
        // ==========================================
        DB::table('detalle_orden')->insert([
            ['Id_Orden' => 1, 'Id_Producto' => 4, 'Cantidad' => 5,  'Precio' => 500.00,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 1, 'Id_Producto' => 6, 'Cantidad' => 10, 'Precio' => 18.00,   'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 2, 'Id_Producto' => 5, 'Cantidad' => 2,  'Precio' => 1200.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 3, 'Id_Producto' => 7, 'Cantidad' => 3,  'Precio' => 380.00,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 4, 'Id_Producto' => 1, 'Cantidad' => 20, 'Precio' => 120.00,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 4, 'Id_Producto' => 2, 'Cantidad' => 50, 'Precio' => 20.00,   'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 5, 'Id_Producto' => 8, 'Cantidad' => 2,  'Precio' => 3500.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 17. FACTURAS
        // ==========================================
        DB::table('facturas')->insert([
            ['Id_Orden' => 1, 'Fecha' => '2025-01-05', 'Total' => 2680.00, 'Estado_Pago' => 'pagado',   'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 2, 'Fecha' => '2025-01-18', 'Total' => 2400.00, 'Estado_Pago' => 'pagado',   'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 3, 'Fecha' => '2025-02-03', 'Total' => 1140.00, 'Estado_Pago' => 'pagado',   'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 4, 'Fecha' => '2025-02-20', 'Total' => 3400.00, 'Estado_Pago' => 'pendiente','created_at' => $now, 'updated_at' => $now],
            ['Id_Orden' => 5, 'Fecha' => '2025-03-01', 'Total' => 7000.00, 'Estado_Pago' => 'pendiente','created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 18. PAGOS
        // ==========================================
        DB::table('pagos')->insert([
            ['Id_Factura' => 1, 'Fecha' => '2025-01-07', 'Monto' => 2680.00, 'Metodo' => 'transferencia', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Factura' => 2, 'Fecha' => '2025-01-20', 'Monto' => 2400.00, 'Metodo' => 'transferencia', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Factura' => 3, 'Fecha' => '2025-02-05', 'Monto' => 1140.00, 'Metodo' => 'efectivo',      'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 19. ÓRDENES DE COMPRA
        // ==========================================
        DB::table('ordenes_compra')->insert([
            ['Id_Proveedor' => 1, 'Id_Almacen' => 1, 'Fecha' => '2025-01-10', 'Estado' => 'recibido',  'created_at' => $now, 'updated_at' => $now],
            ['Id_Proveedor' => 5, 'Id_Almacen' => 1, 'Fecha' => '2025-01-25', 'Estado' => 'recibido',  'created_at' => $now, 'updated_at' => $now],
            ['Id_Proveedor' => 2, 'Id_Almacen' => 2, 'Fecha' => '2025-02-10', 'Estado' => 'pendiente', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 20. DETALLE ÓRDENES DE COMPRA
        // ==========================================
        DB::table('detalle_orden_compra')->insert([
            ['Id_Orden_Compra' => 1, 'Id_Producto' => 1, 'Cantidad' => 100, 'Costo' => 85.00,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden_Compra' => 1, 'Id_Producto' => 2, 'Cantidad' => 200, 'Costo' => 12.50,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden_Compra' => 2, 'Id_Producto' => 3, 'Cantidad' => 50,  'Costo' => 45.00,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Orden_Compra' => 3, 'Id_Producto' => 5, 'Cantidad' => 5,   'Costo' => 780.00, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 21. PROYECTOS
        // ==========================================
        DB::table('proyectos')->insert([
            ['Id_Cliente' => 1, 'Nombre' => 'Construcción Planta Industrial Lima',  'Fecha_Inicio' => '2025-01-15', 'Fecha_Fin' => '2025-06-30', 'Estado' => 'en_progreso', 'Tipo' => 'produccion', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 2, 'Nombre' => 'Instalación Sistema de Bombeo',        'Fecha_Inicio' => '2025-02-01', 'Fecha_Fin' => '2025-04-30', 'Estado' => 'en_progreso', 'Tipo' => 'produccion', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 4, 'Nombre' => 'Consultoría de Procesos Industriales', 'Fecha_Inicio' => '2025-01-01', 'Fecha_Fin' => '2025-03-31', 'Estado' => 'completado',  'Tipo' => 'servicio',   'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 22. PROYECTO PRODUCTOS
        // ==========================================
        DB::table('proyecto_productos')->insert([
            ['Id_Proyecto' => 1, 'Id_Producto' => 1, 'Cantidad' => 50, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 1, 'Id_Producto' => 2, 'Cantidad' => 80, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 1, 'Id_Producto' => 3, 'Cantidad' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 2, 'Id_Producto' => 4, 'Cantidad' => 5,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 2, 'Id_Producto' => 5, 'Cantidad' => 3,  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 23. PROYECTO GASTOS
        // ==========================================
        DB::table('proyecto_gastos')->insert([
            ['Id_Proyecto' => 1, 'Descripcion' => 'Alquiler de grúa',            'Monto' => 3500.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 1, 'Descripcion' => 'Transporte de materiales',    'Monto' => 850.00,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 1, 'Descripcion' => 'Equipos de protección EPP',   'Monto' => 420.00,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 2, 'Descripcion' => 'Instalación eléctrica',       'Monto' => 1200.00, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Proyecto' => 3, 'Descripcion' => 'Viáticos del consultor',      'Monto' => 600.00,  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 24. ASIGNACIONES (empleados a proyectos)
        // ==========================================
        DB::table('asignaciones')->insert([
            ['Id_Empleado' => 7, 'Id_Proyecto' => 1, 'Horas_Asignadas' => 160, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 5, 'Id_Proyecto' => 1, 'Horas_Asignadas' => 120, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 7, 'Id_Proyecto' => 2, 'Horas_Asignadas' => 80,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Empleado' => 1, 'Id_Proyecto' => 3, 'Horas_Asignadas' => 40,  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 25. MOVIMIENTOS DE INVENTARIO
        // ==========================================
        DB::table('movimientos')->insert([
            ['Id_Producto' => 1, 'Id_Proyecto' => 1, 'Tipo' => 'salida_produccion',   'Cantidad' => 30, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 2, 'Id_Proyecto' => 1, 'Tipo' => 'salida_produccion',   'Cantidad' => 50, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 3, 'Id_Proyecto' => 1, 'Tipo' => 'salida_produccion',   'Cantidad' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 4, 'Id_Proyecto' => 2, 'Tipo' => 'salida_produccion',   'Cantidad' => 3,  'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 2, 'Id_Proyecto' => 2, 'Tipo' => 'entrada_devolucion',  'Cantidad' => 5,  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 26. NOTIFICACIONES
        // ==========================================
        DB::table('notificaciones')->insert([
            ['Id_Producto' => 7, 'Cantidad_Requerida' => 10, 'Id_Proyecto' => null, 'Mensaje' => 'Stock bajo de Taladro Industrial: quedan 18 unidades, revisar reposición.',       'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 8, 'Cantidad_Requerida' => 5,  'Id_Proyecto' => null, 'Mensaje' => 'Stock crítico de Laptop Dell: solo 10 unidades disponibles.',                    'created_at' => $now, 'updated_at' => $now],
            ['Id_Producto' => 1, 'Cantidad_Requerida' => 50, 'Id_Proyecto' => 1,    'Mensaje' => 'El proyecto Planta Industrial Lima requiere 50 planchas de acero adicionales.',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // ==========================================
        // 27. OPORTUNIDADES CRM
        // ==========================================
        DB::table('oportunidades_crm')->insert([
            ['Id_Cliente' => 1, 'Titulo' => 'Ampliación de planta fase 2',       'Descripcion' => 'El cliente evalúa ampliar su planta industrial en 2026.',      'Monto_Estimado' => 85000.00, 'Estado' => 'Negociación', 'Fecha_Cierre' => '2025-06-30', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 2, 'Titulo' => 'Sistema de bombeo adicional',        'Descripcion' => 'Segunda fase del proyecto de bombeo en unidad minera norte.',   'Monto_Estimado' => 42000.00, 'Estado' => 'Prospecto',   'Fecha_Cierre' => '2025-09-30', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 3, 'Titulo' => 'Venta de equipos electrónicos',     'Descripcion' => 'Cliente interesado en renovar equipos de cómputo.',             'Monto_Estimado' => 7000.00,  'Estado' => 'Cerrado',     'Fecha_Cierre' => '2025-02-28', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 4, 'Titulo' => 'Consultoría de mejora continua',    'Descripcion' => 'Proyecto de optimización de procesos de manufactura.',          'Monto_Estimado' => 15000.00, 'Estado' => 'Negociación', 'Fecha_Cierre' => '2025-05-31', 'created_at' => $now, 'updated_at' => $now],
            ['Id_Cliente' => 5, 'Titulo' => 'Suministro de insumos de oficina',  'Descripcion' => 'Contrato anual de suministro de papel y útiles.',               'Monto_Estimado' => 3500.00,  'Estado' => 'Prospecto',   'Fecha_Cierre' => '2025-04-30', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('✅ ErpDataSeeder ejecutado correctamente.');
        $this->command->info('📦 Datos insertados en: categorias, proveedores, almacenes, productos, inventario,');
        $this->command->info('   clientes, puestos, empleados, contratos, periodos, nominas, cuenta_contable,');
        $this->command->info('   asientos, asiento_detalle, ordenes, detalle_orden, facturas, pagos,');
        $this->command->info('   ordenes_compra, detalle_orden_compra, proyectos, proyecto_productos,');
        $this->command->info('   proyecto_gastos, asignaciones, movimientos, notificaciones, oportunidades_crm.');
    }
}