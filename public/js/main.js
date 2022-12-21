function inputAdd(elm) {
  const input = $(elm).parent().find("input[type=number]")[0];
  input.value = parseInt(input.value) + 1;
}

function inputMinus(elm, min = 0) {
  const input = $(elm).parent().find("input[type=number]")[0];
  if (input.value <= min) {
    return;
  }
  input.value = parseInt(input.value) - 1;
}
