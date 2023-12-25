export type Sales = { [key: string]: { [key: string]: number } };

export interface Transaction {
    id: string;
    user_id: number;
    grand_total: number;
    date: string;
    operator_name: string;
    total_ticket: TotalTicket;
    operator: Operator;
}

export interface Operator {
    id: number;
    name: string;
    username: string;
    email: string;
}

export interface TotalTicket {
    all: string;
    "1": number;
    "2": string;
    "3": number;
}
