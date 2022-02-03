function calculateChangePercentage(oldValue, newValue) {
  const change = newValue - oldValue;
  return change / (oldValue || 1);
}

const result = calculateChangePercentage(0.2, 10);

console.log(result);
