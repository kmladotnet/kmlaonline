from openpyxl import load_workbook

f = open('donation_output.txt', 'w')

add_item = ("INSERT INTO donation_test "
            "(n_num, n_category, s_title, s_status, s_type, s_owner) "
            "VALUES ({}, {}, '{}', '{}', '{}', '{}')\n")

wb = load_workbook('donations.xlsx')
sheets = wb.get_sheet_names()
for sheet in sheets:
    ws = wb[sheet]
    i = 1
    while ws.cell(row=i, column=1).value is not None:
        n_num = i
        n_category = sheets.index(sheet)
        s_title = ws.cell(row=i, column=1).value
        s_status = ws.cell(row=i, column=2).value if \
            ws.cell(row=i, column=2).value is not None else ""
        s_type = ws.cell(row=i, column=3).value if \
            ws.cell(row=i, column=3).value is not None else ""
        s_owner = ws.cell(row=i, column=4).value if \
            ws.cell(row=i, column=4).value is not None else ""
        # print(add_item.format(n_num, n_category, s_title, s_status, s_type, s_owner))
        f.write(add_item.format(n_num, n_category, s_title, s_status, s_type, s_owner))
        i += 1
f.close()
