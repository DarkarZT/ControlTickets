import pandas as pd;

archivo = pd.ExcelFile(r"C:\Users\Milto\Downloads");

print(archivo)




try:
    archivo = pd.read_excel(rutaExcel, sheet_name="Orders", header=0)
    print(archivo.shape)

except ValueError as error:
    print("Notificacion error: ",error)


r"C:\Users\paplicaciones\Documents\WorkSpace\Bot Excel\Archivo\Export_MINIMACRO_BONOEMPLEADO_COPIA.xlsx"