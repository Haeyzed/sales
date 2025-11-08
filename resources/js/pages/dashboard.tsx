import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { ComposedChart } from 'recharts';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { TrendingDown, TrendingUp, DollarSign, ShoppingCart, ArrowDownCircle, ArrowUpCircle } from 'lucide-react';
import { useMemo } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardProps {
    statistics: {
        revenue: number;
        purchase: number;
        expense: number;
        return: number;
        purchaseReturn: number;
        profit: number;
    };
    cashFlow: {
        paymentReceived: string[];
        paymentSent: string[];
        months: string[];
    };
    yearlyData: {
        yearlySaleAmount: string[];
        yearlyPurchaseAmount: string[];
    };
}

export default function Dashboard({ statistics, cashFlow, yearlyData }: DashboardProps) {
    const formatCurrency = (value: number): string => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(value);
    };

    const cashFlowChartData = useMemo(() => {
        return cashFlow.months.map((month, index) => ({
            month: month.substring(0, 3),
            received: parseFloat(cashFlow.paymentReceived[index] || '0'),
            sent: parseFloat(cashFlow.paymentSent[index] || '0'),
        }));
    }, [cashFlow]);

    const yearlyChartData = useMemo(() => {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return months.map((month, index) => ({
            month,
            sales: parseFloat(yearlyData.yearlySaleAmount[index] || '0'),
            purchases: parseFloat(yearlyData.yearlyPurchaseAmount[index] || '0'),
        }));
    }, [yearlyData]);

    const statsCards = [
        {
            title: 'Revenue',
            value: formatCurrency(statistics.revenue),
            description: 'Total revenue for this month',
            icon: DollarSign,
            trend: statistics.revenue >= 0 ? 'up' : 'down',
        },
        {
            title: 'Profit',
            value: formatCurrency(statistics.profit),
            description: 'Net profit for this month',
            icon: TrendingUp,
            trend: statistics.profit >= 0 ? 'up' : 'down',
        },
        {
            title: 'Purchase',
            value: formatCurrency(statistics.purchase),
            description: 'Total purchases this month',
            icon: ShoppingCart,
            trend: 'neutral',
        },
        {
            title: 'Expense',
            value: formatCurrency(statistics.expense),
            description: 'Total expenses this month',
            icon: TrendingDown,
            trend: 'neutral',
        },
        {
            title: 'Returns',
            value: formatCurrency(statistics.return),
            description: 'Sale returns this month',
            icon: ArrowDownCircle,
            trend: 'neutral',
        },
        {
            title: 'Purchase Returns',
            value: formatCurrency(statistics.purchaseReturn),
            description: 'Purchase returns this month',
            icon: ArrowUpCircle,
            trend: 'neutral',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
                {/* Statistics Cards */}
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    {statsCards.map((stat) => {
                        const Icon = stat.icon;
                        return (
                            <Card key={stat.title}>
                                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                    <CardTitle className="text-sm font-medium">{stat.title}</CardTitle>
                                    <Icon className="h-4 w-4 text-muted-foreground" />
                                </CardHeader>
                                <CardContent>
                                    <div className="text-2xl font-bold">{stat.value}</div>
                                    <p className="text-xs text-muted-foreground mt-1">{stat.description}</p>
                                    {stat.trend !== 'neutral' && (
                                        <div className={`flex items-center mt-2 text-xs ${stat.trend === 'up' ? 'text-green-600' : 'text-red-600'}`}>
                                            {stat.trend === 'up' ? (
                                                <TrendingUp className="h-3 w-3 mr-1" />
                                            ) : (
                                                <TrendingDown className="h-3 w-3 mr-1" />
                                            )}
                                            {stat.trend === 'up' ? 'Positive' : 'Negative'}
                    </div>
                                    )}
                                </CardContent>
                            </Card>
                        );
                    })}
                </div>

                {/* Charts Section */}
                <div className="grid gap-4 md:grid-cols-2">
                    {/* Cash Flow Chart */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Cash Flow (Last 6 Months)</CardTitle>
                            <CardDescription>Payment received vs sent</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ChartContainer
                                config={{
                                    received: {
                                        label: 'Received',
                                        color: 'hsl(var(--chart-1))',
                                    },
                                    sent: {
                                        label: 'Sent',
                                        color: 'hsl(var(--chart-2))',
                                    },
                                }}
                                className="h-[300px] w-full"
                            >
                                <ComposedChart
                                    data={cashFlowChartData}
                                    margin={{ top: 20, right: 30, left: 20, bottom: 5 }}
                                >
                                    <ChartTooltip
                                        cursor={false}
                                        content={<ChartTooltipContent indicator="line" />}
                                    />
                                    <defs>
                                        <linearGradient id="fillReceived" x1="0" y1="0" x2="0" y2="1">
                                            <stop
                                                offset="5%"
                                                stopColor="var(--color-received)"
                                                stopOpacity={0.8}
                                            />
                                            <stop
                                                offset="95%"
                                                stopColor="var(--color-received)"
                                                stopOpacity={0.1}
                                            />
                                        </linearGradient>
                                        <linearGradient id="fillSent" x1="0" y1="0" x2="0" y2="1">
                                            <stop
                                                offset="5%"
                                                stopColor="var(--color-sent)"
                                                stopOpacity={0.8}
                                            />
                                            <stop
                                                offset="95%"
                                                stopColor="var(--color-sent)"
                                                stopOpacity={0.1}
                                            />
                                        </linearGradient>
                                    </defs>
                                    <g id="received">
                                        {cashFlowChartData.map((item, index) => (
                                            <rect
                                                key={`received-${index}`}
                                                x={index * 60 + 20}
                                                y={280 - (item.received / Math.max(...cashFlowChartData.map(d => Math.max(d.received, d.sent))) * 250)}
                                                width={20}
                                                height={(item.received / Math.max(...cashFlowChartData.map(d => Math.max(d.received, d.sent))) * 250)}
                                                fill="url(#fillReceived)"
                                            />
                                        ))}
                                    </g>
                                    <g id="sent">
                                        {cashFlowChartData.map((item, index) => (
                                            <rect
                                                key={`sent-${index}`}
                                                x={index * 60 + 45}
                                                y={280 - (item.sent / Math.max(...cashFlowChartData.map(d => Math.max(d.received, d.sent))) * 250)}
                                                width={20}
                                                height={(item.sent / Math.max(...cashFlowChartData.map(d => Math.max(d.received, d.sent))) * 250)}
                                                fill="url(#fillSent)"
                                            />
                                        ))}
                                    </g>
                                </ComposedChart>
                            </ChartContainer>
                        </CardContent>
                    </Card>

                    {/* Yearly Sales & Purchases Chart */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Yearly Sales & Purchases</CardTitle>
                            <CardDescription>Monthly comparison for current year</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ChartContainer
                                config={{
                                    sales: {
                                        label: 'Sales',
                                        color: 'hsl(var(--chart-1))',
                                    },
                                    purchases: {
                                        label: 'Purchases',
                                        color: 'hsl(var(--chart-2))',
                                    },
                                }}
                                className="h-[300px] w-full"
                            >
                                <ComposedChart
                                    data={yearlyChartData}
                                    margin={{ top: 20, right: 30, left: 20, bottom: 5 }}
                                >
                                    <ChartTooltip
                                        cursor={false}
                                        content={<ChartTooltipContent indicator="line" />}
                                    />
                                    <defs>
                                        <linearGradient id="fillSales" x1="0" y1="0" x2="0" y2="1">
                                            <stop
                                                offset="5%"
                                                stopColor="var(--color-sales)"
                                                stopOpacity={0.8}
                                            />
                                            <stop
                                                offset="95%"
                                                stopColor="var(--color-sales)"
                                                stopOpacity={0.1}
                                            />
                                        </linearGradient>
                                        <linearGradient id="fillPurchases" x1="0" y1="0" x2="0" y2="1">
                                            <stop
                                                offset="5%"
                                                stopColor="var(--color-purchases)"
                                                stopOpacity={0.8}
                                            />
                                            <stop
                                                offset="95%"
                                                stopColor="var(--color-purchases)"
                                                stopOpacity={0.1}
                                            />
                                        </linearGradient>
                                    </defs>
                                    <g id="sales">
                                        {yearlyChartData.map((item, index) => (
                                            <rect
                                                key={`sales-${index}`}
                                                x={index * 25 + 20}
                                                y={280 - (item.sales / Math.max(...yearlyChartData.map(d => Math.max(d.sales, d.purchases))) * 250)}
                                                width={10}
                                                height={(item.sales / Math.max(...yearlyChartData.map(d => Math.max(d.sales, d.purchases))) * 250)}
                                                fill="url(#fillSales)"
                                            />
                                        ))}
                                    </g>
                                    <g id="purchases">
                                        {yearlyChartData.map((item, index) => (
                                            <rect
                                                key={`purchases-${index}`}
                                                x={index * 25 + 35}
                                                y={280 - (item.purchases / Math.max(...yearlyChartData.map(d => Math.max(d.sales, d.purchases))) * 250)}
                                                width={10}
                                                height={(item.purchases / Math.max(...yearlyChartData.map(d => Math.max(d.sales, d.purchases))) * 250)}
                                                fill="url(#fillPurchases)"
                                            />
                                        ))}
                                    </g>
                                </ComposedChart>
                            </ChartContainer>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
