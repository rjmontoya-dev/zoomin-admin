// mockData.ts

export type CandidateRequest = {
  client: string;
  positionName: string;
  headcount: string;
  candidates: number;
  status: string;
  dateCreated: string;
};

export type PaginatedResponse<T> = {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
};

// Generate mock items
export function generateMockRequests(total: number = 15): CandidateRequest[] {
  const samplePositions = ['Developer', 'Designer', 'Manager', 'Analyst', 'Coordinator', 'Specialist'];
  const sampleClients = ['AB', 'CD', 'EF', 'GH', 'IJ', 'KL'];

  return Array.from({ length: total }, (_, i) => ({
    client: sampleClients[i % sampleClients.length],
    positionName: `Test Position ${i + 1}`,
    headcount: samplePositions[i % samplePositions.length],
    candidates: 1,
    status: 'Active',
    dateCreated: `May ${28 - (i % 28)}, 2025`,
  }));
}

// Create mock paginated data
export function getPaginatedMockData(
  page: number = 1,
  perPage: number = 5
): PaginatedResponse<CandidateRequest> {
  const allData = generateMockRequests(15);
  const total = allData.length;
  const start = (page - 1) * perPage;
  const end = start + perPage;
  const data = allData.slice(start, end);

  return {
    current_page: page,
    data,
    first_page_url: `/api/candidate-requests?page=1`,
    from: start + 1,
    last_page: Math.ceil(total / perPage),
    last_page_url: `/api/candidate-requests?page=${Math.ceil(total / perPage)}`,
    next_page_url: page < Math.ceil(total / perPage) ? `/api/candidate-requests?page=${page + 1}` : null,
    path: `/api/candidate-requests`,
    per_page: perPage,
    prev_page_url: page > 1 ? `/api/candidate-requests?page=${page - 1}` : null,
    to: Math.min(end, total),
    total,
  };
}
