export type Sales = { [key: string]: { [key: string]: number } };

export interface Ticket {
    id: number;
    name: string;
    description: string;
    regular_price: number;
    holiday_price: number;
    is_active: boolean;
    can_delete: boolean;
}

export interface Transaction {
    id: string;
    user_id: number;
    grand_total: number;
    date: string;
    operator_name: string;
    total_ticket: any;
    operator: Operator;
}

export interface TransactionDetail {
    id: string;
    total: number;
    ticket_type_id: number;
    ticket_type_name: string;
    qty: string;
}

export interface Operator {
    id: number;
    name: string;
    username: string;
    email: string;
}

export interface TicketType {
    id: number;
    name: string;
}

export interface TotalTicket {
    all: string;
    "1": number;
    "2": string;
    "3": number;
}

export interface Summary {
    qty: number;
    total: number;
    from?: string;
    to?: string;
}
