import Pagination from "./Pagination";
import cls from "@/utils/cls";
import { Pagination as PaginationProp, TableColumn } from "@/types";
import { IconClipboardOff, IconMoodEmpty } from "@tabler/icons-react";

interface TableProps {
    data: Array<any>;
    pagination?: PaginationProp;
    column: TableColumn;
    onClickRow?: Function | undefined;
    markKey?: string;
    className?: string;
    numbering?: boolean;
}

export default function Table({
    data,
    pagination,
    column,
    className = "",
    onClickRow = undefined,
    markKey,
    numbering = true
}: TableProps) {
    return (
        <>
            <div className="w-full overflow-x-auto shadow rounded-lg">
                <table
                    className={cls(
                        "app-table app-table-striped w-full",
                        onClickRow !== undefined ? "app-table-hover" : "",
                        className ? className : ""
                    )}
                >
                    <thead>
                        <tr>
                            { numbering ? <th className="text-center">No.</th> : null }
                            {column.map((col, headIndex) => (
                                <th
                                    key={"header-" + headIndex}
                                    className={cls(
                                        "text-left",
                                        col.className ? col.className : ""
                                    )}
                                >
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
                                {numbering ? <td className="text-center">
                                    {pagination
                                        ? pagination.from + index
                                        : index + 1}
                                </td> : null }
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
                                            className={col.className}
                                        >
                                            {value}
                                        </td>
                                    );
                                })}
                            </tr>
                        ))}
                        {data.length == 0 ? (
                            <tr>
                                <td colSpan={column.length + 1}>
                                    <div className="px-10 py-20 text-center text-gray-500 flex flex-col items-center">
                                        <IconClipboardOff size={"3rem"} />
                                        <span className="mt-5">
                                            Tidak ada data
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        ) : null}
                    </tbody>
                </table>
            </div>
            {pagination ? <Pagination pagination={pagination} /> : null}
        </>
    );
}
