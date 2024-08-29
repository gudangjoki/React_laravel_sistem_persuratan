import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
import SidebarGroup from "../components/SidebarGroup";
import { ChangeEvent, useEffect, useState } from "react";
import { Line } from "react-chartjs-2";
import "chart.js/auto";
import NavbarGroup from "../components/NavbarGroup";
import { useAuth } from "../hooks/useAuth";
// import { useOutletContext } from "react-router-dom";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

interface TransactionData {
  month: string;
  price: number;
}

// interface Role {
//   role: string;
// }

// const labels = [
//   "January",
//   "February",
//   "March",
//   "April",
//   "May",
//   "June",
//   "July",
//   "August",
//   "September",
//   "October",
//   "November",
//   "December",
// ];

const Home = () => {
  // const { role } = useOutletContext<Role>();
  const thisYear = new Date().getFullYear();
  // const [token] = useState<string | null>(localStorage.getItem("token"));
  const token = localStorage.getItem("token");
  const [year, setYear] = useState<number | undefined>(thisYear);
  const [price, setPrice] = useState<TransactionData[]>([]);
  const initialPriceData: TransactionData[] = Array.from(
    { length: 12 },
    (_, index) => ({
      month: (index + 1).toString(),
      price: 0,
    })
  );

  const [priceDefault] = useState<TransactionData[]>(initialPriceData);

  const [months] = useState<string[]>([
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ]);

  const { url } = useAuth();

  // const navigate = useNavigate();

  const data = {
    labels: months,
    datasets: [
      {
        label: "Transaksi",
        backgroundColor: "#064FF0",
        borderColor: "#064FF0",
        data: price
          ? price.map((data) => data.price)
          : priceDefault.map((data) => data.price),
      },
    ],
  };

  const getDataTransaction = async (year: number) => {
    try {
      const baseUrl = `${url}/paid?year=${year}&role=admin`;
      const options = {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
          "ngrok-skip-browser-warning": "69420"
        },
      };
      const response = await fetch(baseUrl, options);
      const data = await response.json();
      console.log(data);
      setPrice(data.data);
      // console.log(data);
    } catch (err) {
      console.error("Error fetching data:", err);
    }
  };

  const yearChange = (e: ChangeEvent<HTMLSelectElement>) => {
    const { value } = e.target;

    const numericRegex = /^[0-9]*$/;

    if (!numericRegex.test(value)) {
      setYear(undefined);
      e.target.value = "";
    } else {
      value.length <= 0 ? setYear(undefined) : setYear(parseInt(value));
    }
  };

  useEffect(() => {
    if (year) {
      getDataTransaction(year);
    }
  }, [year]);

  // useEffect(() => {
  //   console.log(role);
  // }, [role]);

  return (
    <div className="flex bg-gray-100 gap-x-6 p-4 h-screen overflow-y-auto">
      <SidebarGroup />
      <div className="flex-[80%] flex flex-col gap-y-4">
        <NavbarGroup />
        {/* <div className="flex justify-between gap-4 mb-4">
          <div className="col-span-1 bg-white p-4 rounded shadow">
            <p className="font-bold text-lg">Card 1</p>
            <p className="text-sm text-gray-600">Content for card 1</p>
          </div>
          <div className="col-span-1 bg-white p-4 rounded shadow">
            <p className="font-bold text-lg">Card 2</p>
            <p className="text-sm text-gray-600">Content for card 2</p>
          </div>
          <div className="col-span-1 bg-white p-4 rounded shadow">
            <p className="font-bold text-lg">Card 3</p>
            <p className="text-sm text-gray-600">Content for card 3</p>
          </div>
          <div className="col-span-1 bg-white p-4 rounded shadow">
            <p className="font-bold text-lg">Card 4</p>
            <p className="text-sm text-gray-600">Content for card 4</p>
          </div>
        </div> */}
        <div className="bg-white p-4 rounded shadow w-2/3">
          <p className="font-bold text-lg mb-4">Chart</p>
          <Line className="" data={data} />
          <div className="flex flex-col ml-9">
            <label htmlFor="yearSelect">Enter Year:</label>
            <select id="yearSelect" value={year} onChange={yearChange}>
              <option value="2022">2022</option>
              <option value="2023">2023</option>
              <option value="2024">2024</option>
              <option value="2025">2025</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;
