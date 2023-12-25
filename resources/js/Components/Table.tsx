import Pagination from "./Pagination";
import cls from "@/utils/cls";
import { Pagination as PaginationProp, TableColumn } from "@/types";

interface TableProps {
    data: Array<any>;
    pagination?: PaginationProp;
    column: TableColumn;
    onClickRow?: Function | undefined;
    markKey?: string;
    className?: string;
}

export default function Table({
    data,
    pagination,
    column,
    className,
    onClickRow = undefined,
    markKey,
}: TableProps) {
    return (
        <>
            <div className="w-full overflow-x-auto">
                <table
                    className={cls(
                        "app-table app-table-striped w-full",
                        onClickRow !== undefined ? "app-table-hover" : "",
                        className ? className : ""
                    )}
                >
                    <thead>
                        <tr>
                            <th>No.</th>
                            {column.map((col, headIndex) => (
                                <th key={"header-" + headIndex}>
                                    {col.header}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {data.map((dt, index) => (
                            <tr
                                key={"row-" + index}
                                className={cls(
                                    onClickRow !== undefined
                                        ? "cursor-pointer"
                                        : "",
                                    markKey && dt[markKey] == false
                                        ? "danger"
                                        : ""
                                )}
                                onClick={() =>
                                    onClickRow ? onClickRow(dt) : null
                                }
                            >
                                <td>
                                    {pagination
                                        ? pagination.from + index
                                        : index}
                                </td>
                                {column.map((col, cellIndex) => {
                                    const value =
                                        typeof col.value === "function"
                                            ? col.value(dt, index)
                                            : dt[col.value];
                                    return (
                                        <td
                                            key={
                                                "cell-" +
                                                index +
                                                "-" +
                                                cellIndex
                                            }
                                            className={"text-right"}
                                        >
                                            {value}
                                        </td>
                                    );
                                })}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            {pagination ? <Pagination pagination={pagination} /> : null}
        </>
    );
}
